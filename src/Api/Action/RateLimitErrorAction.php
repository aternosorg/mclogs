<?php

namespace Aternos\Mclogs\Api\Action;

use Aternos\Mclogs\Api\Response\ApiError;
use Aternos\Mclogs\Api\Response\ApiResponse;

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