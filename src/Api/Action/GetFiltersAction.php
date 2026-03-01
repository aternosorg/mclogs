<?php

namespace IndifferentKetchup\IBLogs\Api\Action;

use IndifferentKetchup\IBLogs\Api\Response\ApiResponse;
use IndifferentKetchup\IBLogs\Api\Response\FiltersResponse;

class GetFiltersAction extends ApiAction
{
    protected function runApi(): ApiResponse
    {
        return new FiltersResponse();
    }
}