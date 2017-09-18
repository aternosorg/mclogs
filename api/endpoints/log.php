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

$out->success = true;
$out->id = $id->get();
$out->url = "https://mclo.gs/".$out->id;

echo json_encode($out);