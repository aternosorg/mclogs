<?php

namespace Aternos\Mclogs\Api\Action;

use Aternos\Mclogs\Api\Response\ApiResponse;
use Aternos\Mclogs\Api\Response\LimitsResponse;

class GetLimitsAction extends ApiAction
{
    protected function runApi(): ApiResponse
    {
        return new LimitsResponse();
    }
}