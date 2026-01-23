<?php

namespace Aternos\Mclogs\Storage;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;

class CacheStorage extends Storage
{
    public function getName(): StorageName
    {
        return StorageName::CACHE;
    }

    protected function getConfigBackendName(): string
    {
        return Config::getInstance()->get(ConfigKey::STORAGE_CACHE_BACKEND);
    }

    protected function getConfigDefaultTTL(): int
    {
        return Config::getInstance()->get(ConfigKey::STORAGE_CACHE_TTL);
    }
}