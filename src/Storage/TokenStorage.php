<?php

namespace Aternos\Mclogs\Storage;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;

class TokenStorage extends Storage
{
    public function getName(): StorageName
    {
        return StorageName::TOKEN;
    }

    protected function getConfigBackendName(): string
    {
        return Config::getInstance()->get(ConfigKey::STORAGE_TOKEN_BACKEND);
    }

    protected function getConfigDefaultTTL(): int
    {
        return Config::getInstance()->get(ConfigKey::STORAGE_TOKEN_TTL);
    }
}