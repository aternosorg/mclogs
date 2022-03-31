<?php

namespace Storage;

interface StorageInterface
{
    /**
     * Put some data in the storage, returns the (new) id for the data
     *
     * @param string $data
     * @return ?\Id ID or null
     */
    public static function Put(string $data): ?\Id;

    /**
     * Get some data from the storage by id
     *
     * @param \Id $id
     * @return ?string Data or null, e.g. if it doesn't exist
     */
    public static function Get(\Id $id): ?string;

    /**
     * Renew the data to reset the time to live
     *
     * @param \Id $id
     * @return bool Success
     */
    public static function Renew(\Id $id): bool;
}