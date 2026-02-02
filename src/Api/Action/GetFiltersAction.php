<?php

namespace Aternos\Mclogs\Api\Action;

use Aternos\Mclogs\Api\Response\ApiResponse;
use Aternos\Mclogs\Api\Response\FiltersResponse;

class GetFiltersAction extends ApiAction
{
    protected function runApi(): ApiResponse
    {
        return new FiltersResponse();
    }
}