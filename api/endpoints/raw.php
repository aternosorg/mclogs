<?php

use Aternos\Mclogs\Api\Response\ApiError;
use Aternos\Mclogs\Id;
use Aternos\Mclogs\Log;

$urlId = substr($_SERVER['REQUEST_URI'], strlen("/1/raw/"));
$id = new Id($urlId);
$log = Log::find($id);

if(!$log) {
    $error = new ApiError(404, "Log not found.");
    $error->output();
    exit;
}

header('Content-Type: text/plain');
echo $log->getContent();
