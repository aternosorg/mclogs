<?php

namespace Aternos\Mclogs\Api\Response;

use Aternos\Mclogs\Filter\Filter;

class FiltersResponse extends ApiResponse
{
    public function jsonSerialize(): array
    {
        return Filter::getAll();
    }
}