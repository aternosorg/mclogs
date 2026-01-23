<?php

use Aternos\Mclogs\Api\ContentParser;
use Aternos\Mclogs\Api\Response\ApiError;
use Aternos\Mclogs\Api\Response\CodexLogResponse;
use Aternos\Mclogs\Log;

$content = new ContentParser()->getContent();

if ($content instanceof ApiError) {
    $content->output();
    exit;
}

$log = new Log()->setContent($content);

$codexLog = $log->getCodexLog();
$codexLog->setIncludeEntries(false);

new CodexLogResponse($codexLog)->output();
