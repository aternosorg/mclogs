<?php

namespace Aternos\Mclogs\Metrics;

/**
 * Renders samples in the Prometheus text exposition format
 *
 * @see https://prometheus.io/docs/instrumenting/exposition_formats/#text-based-format
 */
class PrometheusRenderer
{
    public const string CONTENT_TYPE = 'text/plain; version=0.0.4; charset=utf-8';

    /**
     * @param Sample[] $samples
     * @return string
     */
    public function render(array $samples): string
    {
        // All samples of a metric have to follow its HELP and TYPE lines as one group
        $samplesByMetric = [];
        foreach ($samples as $sample) {
            $samplesByMetric[$sample->getMetric()->value][] = $sample;
        }

        $output = "";
        foreach ($samplesByMetric as $metricSamples) {
            $metric = $metricSamples[0]->getMetric();
            $output .= "# HELP " . $metric->value . " " . $this->escapeHelp($metric->getHelp()) . "\n";
            $output .= "# TYPE " . $metric->value . " " . $metric->getType()->value . "\n";
            foreach ($metricSamples as $sample) {
                $output .= $this->renderSample($sample) . "\n";
            }
        }
        return $output;
    }

    /**
     * Render the line of a sample, e.g. mclogs_example_total{source="api"} 42
     *
     * @param Sample $sample
     * @return string
     */
    protected function renderSample(Sample $sample): string
    {
        $labelPairs = [];
        foreach ($sample->getLabels() as $labelName => $labelValue) {
            $labelPairs[] = $labelName . '="' . $this->escapeLabelValue($labelValue) . '"';
        }

        $line = $sample->getMetric()->value;
        if (count($labelPairs) > 0) {
            $line .= "{" . implode(",", $labelPairs) . "}";
        }
        return $line . " " . $sample->getValue();
    }

    /**
     * @param string $help
     * @return string
     */
    protected function escapeHelp(string $help): string
    {
        return strtr($help, [
            "\\" => "\\\\",
            "\n" => "\\n"
        ]);
    }

    /**
     * @param string $value
     * @return string
     */
    protected function escapeLabelValue(string $value): string
    {
        return strtr($value, [
            "\\" => "\\\\",
            "\"" => "\\\"",
            "\n" => "\\n"
        ]);
    }
}
