<?php

namespace IndifferentKetchup\IBLogs\Frontend\Cookie;

class SettingsCookie extends Cookie
{
    /**
     * @inheritDoc
     */
    protected function getKey(): string
    {
        return "IBLOGS_SETTINGS";
    }
}