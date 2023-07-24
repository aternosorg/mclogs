<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$out = new stdClass();
$out->success = false;

if (!isset($_POST['content'])) {
    $out->error = "Required POST argument 'content' not found.";
    echo json_encode($out);
    exit;
}

if (empty($_POST['content'])) {
    $out->error = "Required POST argument 'content' is empty.";
    echo json_encode($out);
    exit;
}

$content = $_POST['content'];
$log = new Log();
$id = $log->put($content);

$urls = Config::Get('urls');

$out->success = true;
$out->id = $id->get();
$out->url = $urls['baseUrl'] . "/" . $out->id;
$out->raw = $urls['apiBaseUrl'] . "/1/raw/" . $out->id;

echo json_encode($out);
