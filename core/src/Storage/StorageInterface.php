<?php

namespace Storage;

interface StorageInterface
{
    /**
     * Put some data in the storage, returns the (new) id for the data
     *
     * @param string $data
     * @return \Id|boolean ID or false
     */
    public static function Put(string $data): \Id;

    /**
     * Get some data from the storage by id
     *
     * @param \Id $id
     * @return string|false Data or false, e.g. if it doesn't exist
     */
    public static function Get(\Id $id);

    /**
     * Renew the data to reset the time to live
     *
     * @param \Id $id
     * @return bool Success
     */
    public static function Renew(\Id $id): bool;
}