<?php

namespace Aternos\Mclogs\Api\Action;

use Aternos\Mclogs\Api\Response\ApiError;
use Aternos\Mclogs\Api\Response\ApiResponse;

class EndpointNotFoundAction extends ApiAction
{
    protected function runApi(): ApiResponse
    {
        return new ApiError(404, "Could not find endpoint.");
    }
}