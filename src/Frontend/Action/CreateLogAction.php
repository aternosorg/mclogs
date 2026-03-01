<?php

namespace IndifferentKetchup\IBLogs\Frontend\Action;

use IndifferentKetchup\IBLogs\Util\URL;

class CreateLogAction extends \IndifferentKetchup\IBLogs\Api\Action\CreateLogAction
{
    protected bool $includeCookie = true;
    protected bool $includeToken = false;

    protected function getAllowedOrigin(): string
    {
        return URL::getBase()->toString();
    }

    protected function shouldAllowCredentials(): bool
    {
        return true;
    }
}