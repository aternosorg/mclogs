<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$out = new stdClass();
$out->success = false;

$postMaxSize = trim(ini_get("post_max_size"));
$last = strtolower($postMaxSize[strlen($postMaxSize)-1]);
switch($last) {
    /** @noinspection PhpMissingBreakStatementInspection */
    case 'g':
        $postMaxSize *= 1024;
    /** @noinspection PhpMissingBreakStatementInspection */
    case 'm':
        $postMaxSize *= 1024;
    case 'k':
        $postMaxSize *= 1024;
}

if (isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > $postMaxSize) {
    http_response_code(413);
    $out->error = "Required POST argument 'content' exceeds upload limit.";
    echo json_encode($out);
    exit;
}

if (!isset($_POST['content'])) {
    http_response_code(400);
    $out->error = "Required POST argument 'content' not found.";
    echo json_encode($out);
    exit;
}

if (empty($_POST['content'])) {
    http_response_code(400);
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
$out->raw = "https://api.mclo.gs/1/raw/".$out->id;

echo json_encode($out);