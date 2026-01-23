<?php

namespace Aternos\Mclogs\Storage;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;

abstract class Storage implements StorageInterface
{
    /**
     * @return int
     */
    protected function getStorageTime(): int
    {
        return Config::getInstance()->get(ConfigKey::STORAGE_TIME);
    }

    /**
     * @return int
     */
    protected function getStorageExpiryTimestamp(): int
    {
        return time() + $this->getStorageTime();
    }
}