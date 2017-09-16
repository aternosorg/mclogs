<?php

interface Storage
{

    /**
     * Put some data in the storage, returns the (new) id for the data
     *
     * @param string $data
     * @return string|boolean ID or false
     */
    public static function Put(string $data): string;

    /**
     * Get some data from the storage by id
     *
     * @param string $id
     * @return string|false Data or false, e.g. if it doesn't exist
     */
    public static function Get(string $id): string;

    /**
     * Renew the data to reset the time to live
     *
     * @param string $id
     * @return bool Success
     */
    public static function Renew(string $id): bool;
}