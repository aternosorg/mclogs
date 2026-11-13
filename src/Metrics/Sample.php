<?php

namespace Aternos\Mclogs\Metrics;

/**
 * A single value of a metric for one set of labels
 */
class Sample
{
    /**
     * @param Metric $metric
     * @param array<string, string> $labels label name => label value
     * @param int $value
     */
    public function __construct(
        protected Metric $metric,
        protected array  $labels,
        protected int    $value
    )
    {
    }

    /**
     * @return Metric
     */
    public function getMetric(): Metric
    {
        return $this->metric;
    }

    /**
     * @return array<string, string>
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
