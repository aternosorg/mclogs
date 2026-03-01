<?php

namespace IndifferentKetchup\IBLogs\Api\Action;

use IndifferentKetchup\IBLogs\Api\Response\ApiResponse;
use IndifferentKetchup\IBLogs\Api\Response\LimitsResponse;

class GetLimitsAction extends ApiAction
{
    protected function runApi(): ApiResponse
    {
        return new LimitsResponse();
    }
}