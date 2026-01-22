<?php

use Aternos\Mclogs\ApiError;
use Aternos\Mclogs\Id;
use Aternos\Mclogs\Log;

$urlId = substr($_SERVER['REQUEST_URI'], strlen("/1/insights/"));
$id = new Id($urlId);
$log = new Log($id);

if (!$log->exists()) {
    $error = new ApiError(404, "Log not found.");
    $error->output();
}

$log->renew();

$codexLog = $log->get();
$codexLog->setIncludeEntries(false);

header('Content-Type: application/json');
echo json_encode($codexLog);
