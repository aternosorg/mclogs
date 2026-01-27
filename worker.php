<?php

use Aternos\Mclogs\Api\ApiRouter;
use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Frontend\FrontendRouter;
use Aternos\Mclogs\Storage\MongoDBClient;
use Aternos\Mclogs\Util\URL;

require_once __DIR__ . '/vendor/autoload.php';

MongoDBClient::getInstance()->ensureIndexes();

$requestCount = 0;
$maxRequests = Config::getInstance()->get(ConfigKey::WORKER_REQUESTS);

do {
    $running = \frankenphp_handle_request(function () {

        MongoDBClient::getInstance()->reset();
        URL::clear();

        if (URL::isApi()) {
            ApiRouter::getInstance()->run();
        } else {
            FrontendRouter::getInstance()->run();
        }
    });

    gc_collect_cycles();

    $requestCount++;
} while ($running && $requestCount < $maxRequests);