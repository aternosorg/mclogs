<?php

namespace Aternos\Mclogs\Storage\Backend;

use Aternos\Mclogs\Storage\StorageName;

abstract class StorageBackend implements StorageBackendInterface
{
    /**
     * @inheritDoc
     */
    public function __construct(protected StorageName $name, protected int $defaultTTL)
    {
    }

    /**
     * @param int|null $ttl
     * @return int
     */
    public function getTTL(?int $ttl = null): int
    {
        return $ttl ?? $this->defaultTTL;
    }

    /**
     * @param int|null $ttl
     * @return int
     */
    public function getTTLExpiryTimestamp(?int $ttl = null): int
    {
        return time() + $this->getTTL($ttl);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name->value;
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return $this->get($id) !== null;
    }
}