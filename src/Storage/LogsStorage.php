<?php

namespace Aternos\Mclogs\Storage;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;

class LogsStorage extends Storage
{
    public function getName(): StorageName
    {
        return StorageName::LOGS;
    }

    protected function getConfigBackendName(): string
    {
        return Config::getInstance()->get(ConfigKey::STORAGE_LOGS_BACKEND);
    }

    protected function getConfigDefaultTTL(): int
    {
        return Config::getInstance()->get(ConfigKey::STORAGE_LOGS_TTL);
    }
}