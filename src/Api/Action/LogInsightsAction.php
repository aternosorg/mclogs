<?php

namespace Aternos\Mclogs\Api\Action;

use Aternos\Mclogs\Api\Response\ApiError;
use Aternos\Mclogs\Api\Response\ApiResponse;
use Aternos\Mclogs\Api\Response\CodexLogResponse;
use Aternos\Mclogs\Id;
use Aternos\Mclogs\Log;
use Aternos\Mclogs\Util\URL;

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