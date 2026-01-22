<?php

use Aternos\Mclogs\ApiError;
use Aternos\Mclogs\Id;
use Aternos\Mclogs\Log;

$urlId = substr($_SERVER['REQUEST_URI'], strlen("/1/raw/"));
$id = new Id($urlId);
$log = new Log($id);

if(!$log->exists()) {
    $error = new ApiError(404, "Log not found.");
    $error->output();
}

$log->renew();

header('Content-Type: text/plain');
echo $log->get()->getLogfile()->getContent();
