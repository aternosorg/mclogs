<?php

namespace IndifferentKetchup\IBLogs\Api\Response;

use IndifferentKetchup\IBLogs\Filter\Filter;

class FiltersResponse extends ApiResponse
{
    public function jsonSerialize(): array
    {
        return Filter::getAll();
    }
}