<?php

use Aternos\Mclogs\ApiError;
use Aternos\Mclogs\ContentParser;
use Aternos\Mclogs\Log;

$content = (new ContentParser())->getContent();

if ($content instanceof ApiError) {
    $content->output();
}

$log = new Log();
$log->setData($content);

$codexLog = $log->getCodexLog();
$codexLog->setIncludeEntries(false);

header('Content-Type: application/json');
echo json_encode($codexLog);
