<?php

namespace IndifferentKetchup\IBLogs\Frontend\Action;

use IndifferentKetchup\IBLogs\Frontend\Cookie\TokenCookie;
use IndifferentKetchup\IBLogs\Log;
use IndifferentKetchup\IBLogs\Util\URL;

class DeleteLogAction extends \IndifferentKetchup\IBLogs\Api\Action\DeleteLogAction
{
    protected function getAllowedOrigin(): string
    {
        return URL::getBase()->toString();
    }

    protected function shouldAllowCredentials(): bool
    {
        return true;
    }

    protected function getRequestToken(): ?string
    {
        return new TokenCookie()->getValue();
    }

    protected function handleDeletedLog(Log $log): void
    {
        new TokenCookie()->setLog($log)->delete();
    }
}