<?php

namespace Aternos\Mclogs\Api\Action;

use Aternos\Mclogs\Api\ContentParser;
use Aternos\Mclogs\Api\Response\ApiError;
use Aternos\Mclogs\Api\Response\ApiResponse;
use Aternos\Mclogs\Api\Response\CodexLogResponse;
use Aternos\Mclogs\Log;

class AnalyseLogAction extends ApiAction
{
    public function runApi(): ApiResponse
    {
        $data = new ContentParser()->getContent();

        if ($data instanceof ApiError) {
            return $data;
        }

        $content = $data['content'];
        $log = new Log()->setContent($content);

        return new CodexLogResponse($log->getCodexLog());
    }
}