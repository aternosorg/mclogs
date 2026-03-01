<?php

namespace IndifferentKetchup\IBLogs\Api\Action;

use IndifferentKetchup\IBLogs\Api\Response\ApiError;
use IndifferentKetchup\IBLogs\Api\Response\ApiResponse;
use IndifferentKetchup\IBLogs\Api\Response\CodexLogResponse;
use IndifferentKetchup\IBLogs\Id;
use IndifferentKetchup\IBLogs\Log;
use IndifferentKetchup\IBLogs\Util\URL;

class LogInsightsAction extends ApiAction
{
    /**
     * @return ApiResponse
     */
    protected function runApi(): ApiResponse
    {
        $id = new Id(URL::getLastPathPart());
        $log = Log::find($id);

        if (!$log) {
            return new ApiError(404, "Log not found.");
        }

        $codexLog = $log->getCodexLog();
        $codexLog->setIncludeEntries(false);

        return new CodexLogResponse($codexLog);
    }
}