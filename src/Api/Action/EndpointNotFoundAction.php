<?php

namespace IndifferentKetchup\IBLogs\Api\Action;

use IndifferentKetchup\IBLogs\Api\Response\ApiError;
use IndifferentKetchup\IBLogs\Api\Response\ApiResponse;

class EndpointNotFoundAction extends ApiAction
{
    protected function runApi(): ApiResponse
    {
        return new ApiError(404, "Could not find endpoint.");
    }
}