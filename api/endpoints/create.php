<?php

use Aternos\Mclogs\Api\ContentParser;
use Aternos\Mclogs\Api\Response\ApiError;
use Aternos\Mclogs\Api\Response\LogResponse;
use Aternos\Mclogs\Log;

$data = new ContentParser()->getContent();

if ($data instanceof ApiError) {
    $data->output();
    exit;
}

$content = $data['content'];
$metadata = [];
if (isset($data['metadata']) && is_array($data['metadata'])) {
    $metadata = $data['metadata'];
}
$source = null;
if (isset($data['source']) && is_string($data['source'])) {
    $source = $data['source'];
}

$log = Log::create($content, $metadata, $source);

$response = new LogResponse($log, true);
$response->output();
