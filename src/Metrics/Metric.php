<?php

namespace Aternos\Mclogs\Metrics;

/**
 * All metrics the metrics endpoint can expose, the value is the name of the metric in Prometheus
 */
enum Metric: string
{
    case LOGS_STORED = 'mclogs_logs_stored';

    /**
     * @return MetricType
     */
    public function getType(): MetricType
    {
        return match ($this) {
            Metric::LOGS_STORED => MetricType::GAUGE,
        };
    }

    /**
     * Get the description that is shown next to the metric in Prometheus
     *
     * @return string
     */
    public function getHelp(): string
    {
        return match ($this) {
            Metric::LOGS_STORED => 'Estimated number of logs that are currently stored.',
        };
    }
}
