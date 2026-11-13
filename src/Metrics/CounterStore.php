<?php

namespace Aternos\Mclogs\Metrics;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Storage\MongoDBClient;
use Aternos\Mclogs\Util\Singleton;
use Exception;
use InvalidArgumentException;

/**
 * Counters that are stored in MongoDB, because workers restart regularly
 * and several instances count the same events
 *
 * Every time series (metric name and label set) is one document in the metrics collection:
 * {_id: {name: "mclogs_example_total", labels: {source: "api"}}, value: 42}
 */
class CounterStore
{
    use Singleton;

    /**
     * Label names as Prometheus allows them, e.g. "source" or "log_type", but not "log-type" or "2nd"
     */
    protected const string LABEL_NAME_PATTERN = '/^[a-zA-Z_][a-zA-Z0-9_]*$/D';

    /**
     * Increments of the current request that are not written to MongoDB yet
     *
     * @var array<string, array{id: array, amount: int}> series key => series id and amount
     */
    protected array $pending = [];

    /**
     * Count an event, the increment is only written to MongoDB by the next flush()
     *
     * Does nothing when metrics are disabled.
     *
     * @param Metric $metric
     * @param array<string, string> $labels label name => label value
     * @param int $amount
     * @return void
     */
    public function increment(Metric $metric, array $labels = [], int $amount = 1): void
    {
        if (!Config::getInstance()->isMetricsEnabled()) {
            return;
        }
        if ($metric->getType() !== MetricType::COUNTER) {
            throw new InvalidArgumentException("Metric " . $metric->value . " is not a counter and cannot be incremented.");
        }

        $seriesId = $this->getSeriesId($metric, $labels);
        $seriesKey = json_encode($seriesId);
        if (!isset($this->pending[$seriesKey])) {
            $this->pending[$seriesKey] = ['id' => $seriesId, 'amount' => 0];
        }
        $this->pending[$seriesKey]['amount'] += $amount;
    }

    /**
     * Write all pending increments to MongoDB in one bulk write
     *
     * A failure is only logged, counting must never break the request that is counted.
     *
     * @return void
     */
    public function flush(): void
    {
        if (count($this->pending) === 0) {
            return;
        }

        $operations = [];
        foreach ($this->pending as $increment) {
            $operations[] = ['updateOne' => [
                ['_id' => $increment['id']],
                ['$inc' => ['value' => $increment['amount']]],
                ['upsert' => true]
            ]];
        }
        $this->pending = [];

        try {
            MongoDBClient::getInstance()->getMetricsCollection()->bulkWrite($operations, ['ordered' => false]);
        } catch (Exception $e) {
            error_log("Failed to write " . count($operations) . " metric counter increments to MongoDB: " . $e->getMessage());
        }
    }

    /**
     * Get the current values of all stored counters
     *
     * @return Sample[]
     */
    public function getSamples(): array
    {
        $documents = MongoDBClient::getInstance()->getMetricsCollection()->find([], [
            'sort' => ['_id' => 1],
            'typeMap' => ['root' => 'array', 'document' => 'array']
        ]);

        $samples = [];
        foreach ($documents as $document) {
            $metric = Metric::tryFrom($document['_id']['name']);
            if ($metric === null) {
                // Counter of a metric that does not exist in the code anymore
                continue;
            }
            $samples[] = new Sample($metric, $document['_id']['labels'], $document['value']);
        }
        return $samples;
    }

    /**
     * Get the document id of a time series
     *
     * The labels are sorted, so the same label set always results in the same id.
     *
     * @param Metric $metric
     * @param array<string, string> $labels
     * @return array{name: string, labels: object}
     */
    protected function getSeriesId(Metric $metric, array $labels): array
    {
        $sortedLabels = [];
        foreach ($labels as $labelName => $labelValue) {
            if (preg_match(static::LABEL_NAME_PATTERN, (string)$labelName) !== 1) {
                throw new InvalidArgumentException("Invalid label name \"" . $labelName . "\" for metric " . $metric->value . ".");
            }
            $sortedLabels[$labelName] = (string)$labelValue;
        }
        ksort($sortedLabels);

        return [
            'name' => $metric->value,
            // Cast to object, because an empty array would be stored as a list instead of a document
            'labels' => (object)$sortedLabels
        ];
    }
}
