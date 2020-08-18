<?php

header('Access-Control-Allow-Origin: *');

$urlId = substr($_SERVER['REQUEST_URI'], strlen("/1/raw/"));
$id = new Id($urlId);
$log = new Log($id);

if(!$log->exists()) {
    header('Content-Type: application/json');
    http_response_code(404);

    $out = new stdClass();
    $out->success = false;
    $out->error = "Log not found.";

    echo json_encode($out);
    exit;
}

$log->renew();

header('Content-Type: text/plain');
echo $log->get()->getLogfile()->getContent();