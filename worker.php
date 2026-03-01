<?php

use IndifferentKetchup\IBLogs\Api\ApiRouter;
use IndifferentKetchup\IBLogs\Config\Config;
use IndifferentKetchup\IBLogs\Config\ConfigKey;
use IndifferentKetchup\IBLogs\Frontend\FrontendRouter;
use IndifferentKetchup\IBLogs\Storage\MongoDBClient;
use IndifferentKetchup\IBLogs\Util\URL;

require_once __DIR__ . '/vendor/autoload.php';

try {
    MongoDBClient::getInstance()->ensureIndexes();
} catch (Exception $e) {
    error_log("Failed to ensure MongoDB indexes: " . $e->getMessage());
}

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