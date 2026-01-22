<?php

namespace Aternos\Mclogs\Storage;

interface StorageInterface
{
    /**
     * Put some data in the storage, returns the (new) id for the data
     *
     * @param string $data
     * @return ?\Aternos\Mclogs\Id ID or null
     */
    public static function Put(string $data): ?\Aternos\Mclogs\Id;

    /**
     * Get some data from the storage by id
     *
     * @param \Aternos\Mclogs\Id $id
     * @return ?string Data or null, e.g. if it doesn't exist
     */
    public static function Get(\Aternos\Mclogs\Id $id): ?string;

    /**
     * Renew the data to reset the time to live
     *
     * @param \Aternos\Mclogs\Id $id
     * @return bool Success
     */
    public static function Renew(\Aternos\Mclogs\Id $id): bool;
}