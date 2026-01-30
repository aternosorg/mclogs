<?php

namespace Aternos\Mclogs\Frontend\Action;

use Aternos\Mclogs\Frontend\Cookie\TokenCookie;
use Aternos\Mclogs\Log;
use Aternos\Mclogs\Util\URL;

class DeleteLogAction extends \Aternos\Mclogs\Api\Action\DeleteLogAction
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