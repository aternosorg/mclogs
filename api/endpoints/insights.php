<?php

use Aternos\Mclogs\Api\Response\ApiError;
use Aternos\Mclogs\Api\Response\CodexLogResponse;
use Aternos\Mclogs\Id;
use Aternos\Mclogs\Log;

$urlId = substr($_SERVER['REQUEST_URI'], strlen("/1/insights/"));
$id = new Id($urlId);
$log = Log::find($id);

if (!$log) {
    $error = new ApiError(404, "Log not found.");
    $error->output();
    exit;
}

$codexLog = $log->getCodexLog();
$codexLog->setIncludeEntries(false);

new CodexLogResponse($codexLog)->output();
