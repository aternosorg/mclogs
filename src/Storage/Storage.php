<?php

namespace Aternos\Mclogs\Storage;

use Aternos\Mclogs\Storage\Backend\Filesystem;
use Aternos\Mclogs\Storage\Backend\MongoDB;
use Aternos\Mclogs\Storage\Backend\Redis;
use Aternos\Mclogs\Storage\Backend\StorageBackendInterface;

abstract class Storage
{
    protected const array BACKENDS = [
        "filesystem" => Filesystem::class,
        "mongodb" => MongoDB::class,
        "redis" => Redis::class
    ];

    protected ?StorageBackendInterface $storageBackend = null;

    abstract public function getName(): StorageName;

    abstract protected function getConfigBackendName(): string;
    abstract protected function getConfigDefaultTTL(): int;

    /**
     * @return StorageBackendInterface
     */
    protected function getBackend(): StorageBackendInterface
    {
        if ($this->storageBackend) {
            return $this->storageBackend;
        }

        $backendClass = $this->getBackendClass($this->getConfigBackendName());
        /** @var StorageBackendInterface $backend */
        $backend = new $backendClass(StorageName::LOGS, $this->getConfigDefaultTTL());

        return $this->storageBackend = $backend;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getBackendClass(string $name): string
    {
        if (!isset(self::BACKENDS[$name])) {
            throw new \InvalidArgumentException("Storage backend '$name' is not defined.");
        }
        return self::BACKENDS[$name];
    }
}