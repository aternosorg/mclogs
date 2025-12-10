<?php

$content = (new ContentParser())->getContent();

if ($content instanceof ApiError) {
    $content->output();
}

$log = new Log();
$id = $log->put($content);

$urls = Config::Get('urls');

$out = new stdClass();
$out->success = true;
$out->id = $id->get();
$out->url = $urls['baseUrl'] . "/" . $out->id;
$out->raw = $urls['apiBaseUrl'] . "/1/raw/" . $out->id;

header('Content-Type: application/json');
echo json_encode($out);
