<?php

use Aternos\Mclogs\Api\Response\ApiError;
use Aternos\Mclogs\Api\Response\LogResponse;
use Aternos\Mclogs\Id;
use Aternos\Mclogs\Log;
use Aternos\Mclogs\Util\URL;

$urlId = substr(URL::getCurrent()->getPath(), strlen("/1/log/"));
$id = new Id($urlId);
$log = Log::find($id);

if(!$log) {
    $error = new ApiError(404, "Log not found.");
    $error->output();
    exit;
}

new LogResponse($log)->output();
