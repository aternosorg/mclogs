<?php

namespace Aternos\Mclogs\Frontend\Cookie;

class SettingsCookie extends Cookie
{
    /**
     * @inheritDoc
     */
    protected function getKey(): string
    {
        return "MCLOGS_SETTINGS";
    }
}