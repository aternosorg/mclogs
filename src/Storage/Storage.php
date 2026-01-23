<?php

namespace Aternos\Mclogs\Storage;

use Aternos\Mclogs\Storage\Backend\Filesystem;
use Aternos\Mclogs\Storage\Backend\MongoDB;
use Aternos\Mclogs\Storage\Backend\Redis;
use Aternos\Mclogs\Storage\Backend\StorageBackendInterface;

abstract class Storage
{
    /**
     * @var array<string, class-string<StorageBackendInterface>>
     */
    protected const array BACKENDS = [
        "filesystem" => Filesystem::class,
        "mongodb" => MongoDB::class,
        "redis" => Redis::class
    ];

    protected ?StorageBackendInterface $storageBackend = null;

    abstract public function getName(): StorageName;

    abstract protected function getBackendName(): string;
    abstract protected function getDefaultTTL(): int;

    /**
     * @return StorageBackendInterface
     */
    public function getBackend(): StorageBackendInterface
    {
        if ($this->storageBackend) {
            return $this->storageBackend;
        }

        $backendClass = $this->getBackendClass();
        /** @var StorageBackendInterface $backend */
        $backend = new $backendClass($this->getName(), $this->getDefaultTTL());

        return $this->storageBackend = $backend;
    }

    /**
     * @return string
     */
    protected function getBackendClass(): string
    {
        $name = $this->getBackendName();

        if (!isset(self::BACKENDS[$name])) {
            throw new \InvalidArgumentException("Storage backend '$name' is not defined.");
        }
        return self::BACKENDS[$name];
    }
}