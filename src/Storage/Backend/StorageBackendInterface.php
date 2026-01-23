<?php

namespace Aternos\Mclogs\Storage\Backend;

use Aternos\Mclogs\Storage\StorageName;

interface StorageBackendInterface
{
    /**
     * Storage constructor.
     *
     * Initialize the storage with a name and time to live (in seconds)
     *
     * @param StorageName $name
     * @param int $defaultTTL
     */
    public function __construct(StorageName $name, int $defaultTTL);

    /**
     * Get the storage id
     *
     * @return StorageBackendId
     */
    public function getId(): StorageBackendId;

    /**
     * Put some data in the storage, returns the (new) id for the data
     *
     * @param string $id
     * @param string $data
     * @param int|null $ttl
     * @return bool
     */
    public function put(string $id, string $data, ?int $ttl = null): bool;

    /**
     * Get some data from the storage by id
     *
     * @param string $id
     * @return string|null
     */
    public function get(string $id): ?string;

    /**
     * Check if some data exists in the storage by id
     *
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool;

    /**
     * Delete some data from the storage by id
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

    /**
     * Renew the data to reset the time to live
     *
     * @param string $id
     * @param int|null $ttl
     * @return bool
     */
    public function renew(string $id, ?int $ttl = null): bool;
}