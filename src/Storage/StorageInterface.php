<?php

namespace Aternos\Mclogs\Storage;

use Aternos\Mclogs\Id;

interface StorageInterface
{
    /**
     * Put some data in the storage, returns the (new) id for the data
     *
     * @param string $data
     * @return Id|null
     */
    public function put(string $data): ?Id;

    /**
     * Get some data from the storage by id
     *
     * @param Id $id
     * @return string|null
     */
    public function get(Id $id): ?string;

    /**
     * Renew the data to reset the time to live
     *
     * @param Id $id
     * @return bool
     */
    public function renew(Id $id): bool;
}