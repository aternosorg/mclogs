<?php

header('Access-Control-Allow-Origin: *');

$urlId = substr($_SERVER['REQUEST_URI'], strlen("/1/insights/"));
$id = new Id($urlId);
$log = new Log($id);
header('Content-Type: application/json');

if(!$log->exists()) {
    http_response_code(404);

    $out = new stdClass();
    $out->success = false;
    $out->error = "Log not found.";

    echo json_encode($out);
    exit;
}

$log->renew();

$codexLog = $log->get();
$codexLog->setIncludeEntries(false);
echo json_encode($codexLog);