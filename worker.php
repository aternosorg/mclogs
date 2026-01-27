<?php

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Storage\MongoDBClient;
use Aternos\Mclogs\Util\URL;

require_once __DIR__ . '/vendor/autoload.php';

$requestCount = 0;
$maxRequests = Config::getInstance()->get(ConfigKey::WORKER_REQUESTS);

do {
    $running = \frankenphp_handle_request(function () {

        MongoDBClient::getInstance()->reset();
        URL::clear();

        if (URL::isApi()) {
            require __DIR__ . '/api/index.php';
        } else {
            require __DIR__ . '/web/index.php';
        }
    });

    gc_collect_cycles();

    $requestCount++;
} while ($running && $requestCount < $maxRequests);