<?php

namespace Aternos\Mclogs\Storage;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Id;
use Aternos\Mclogs\Storage\Backend\StorageBackendId;

class LogsStorage extends Storage
{
    protected ?StorageBackendId $backendId = null;

    public function getName(): StorageName
    {
        return StorageName::LOGS;
    }

    /**
     * @param StorageBackendId|null $backendId
     * @return $this
     */
    public function setBackendId(?StorageBackendId $backendId): static
    {
        $this->backendId = $backendId;
        return $this;
    }

    protected function getBackendClass(): string
    {
        if ($this->backendId) {
            return $this->backendId->getClass();
        }
        return parent::getBackendClass();
    }

    protected function getBackendName(): string
    {
        return Config::getInstance()->get(ConfigKey::STORAGE_LOGS_BACKEND);
    }

    protected function getDefaultTTL(): int
    {
        return Config::getInstance()->get(ConfigKey::STORAGE_LOGS_TTL);
    }

    public function getLog(Id $id): ?string
    {
        return $this->getBackend()->get($id->getRaw());
    }
}