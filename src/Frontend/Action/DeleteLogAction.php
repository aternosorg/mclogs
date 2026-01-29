<?php

namespace Aternos\Mclogs\Frontend\Action;

use Aternos\Mclogs\Frontend\Cookie\TokenCookie;
use Aternos\Mclogs\Log;
use Aternos\Mclogs\Util\URL;

class DeleteLogAction extends \Aternos\Mclogs\Api\Action\DeleteLogAction
{
    protected TokenCookie $tokenCookie;

    public function __construct()
    {
        $this->tokenCookie = new TokenCookie();
    }

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
        return $this->tokenCookie->getValue();
    }

    protected function handleDeletedLog(Log $log): void
    {
        $this->tokenCookie->setLog($log)->delete();
    }
}