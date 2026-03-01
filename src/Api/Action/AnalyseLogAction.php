<?php

namespace IndifferentKetchup\IBLogs\Api\Action;

use IndifferentKetchup\IBLogs\Api\LogContentParser;
use IndifferentKetchup\IBLogs\Api\Response\ApiError;
use IndifferentKetchup\IBLogs\Api\Response\ApiResponse;
use IndifferentKetchup\IBLogs\Api\Response\CodexLogResponse;
use IndifferentKetchup\IBLogs\Log;

class AnalyseLogAction extends ApiAction
{
    public function runApi(): ApiResponse
    {
        $data = new LogContentParser()->getContent();

        if ($data instanceof ApiError) {
            return $data;
        }

        $content = $data['content'];
        $log = new Log()->setContent($content);

        return new CodexLogResponse($log->getCodexLog());
    }
}
