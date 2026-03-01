<?php

namespace IndifferentKetchup\IBLogs\Api\Action;

use IndifferentKetchup\IBLogs\Api\Response\ApiError;
use IndifferentKetchup\IBLogs\Api\Response\ApiResponse;

class RateLimitErrorAction extends ApiAction
{
    protected function runApi(): ApiResponse
    {
        return new ApiError(
            429,
            "Unfortunately you have exceeded the rate limit for the current time period. Please try again later."
        );
    }
}