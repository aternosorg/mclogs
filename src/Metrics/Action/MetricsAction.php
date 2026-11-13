<?php

namespace Aternos\Mclogs\Metrics\Action;

use Aternos\Mclogs\Metrics\CounterStore;
use Aternos\Mclogs\Metrics\Metric;
use Aternos\Mclogs\Metrics\PrometheusRenderer;
use Aternos\Mclogs\Metrics\Sample;
use Aternos\Mclogs\Router\Action;
use Aternos\Mclogs\Storage\MongoDBClient;

class MetricsAction extends Action
{
    public function run(): bool
    {
        $samples = CounterStore::getInstance()->getSamples();

        $storedLogs = MongoDBClient::getInstance()->getLogsCollection()->estimatedDocumentCount();
        $samples[] = new Sample(Metric::LOGS_STORED, [], $storedLogs);

        header('Content-Type: ' . PrometheusRenderer::CONTENT_TYPE);
        echo new PrometheusRenderer()->render($samples);
        return true;
    }
}
