<?php

namespace Aternos\Mclogs\Frontend\Action;

use Aternos\Mclogs\Util\URL;

class CreateLogAction extends \Aternos\Mclogs\Api\Action\CreateLogAction
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