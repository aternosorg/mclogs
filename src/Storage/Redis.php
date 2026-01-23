<?php

namespace Aternos\Mclogs\Storage;

use Aternos\Mclogs\Client\RedisClient;

class Redis extends RedisClient implements StorageInterface {

    /**
     * Put some data in the storage, returns the (new) id for the data
     *
     * @param string $data
     * @return ?\Aternos\Mclogs\Id ID or null
     */
    public static function Put(string $data): ?\Aternos\Mclogs\Id
    {
        self::Connect();
        $config = \Aternos\Mclogs\Config::Get("storage");

        $id = new \Aternos\Mclogs\Id();
        $id->setStorageId("r");

        do {
            $id->generateNew();
        } while(self::Get($id) !== null);

        self::$connection->setEx($id->getRaw(), $config['storageTime'], $data);
        return $id;
    }

    /**
     * Get some data from the storage by id
     *
     * @param \Aternos\Mclogs\Id $id
     * @return ?string Data or false, e.g. if it doesn't exist
     */
    public static function Get(\Aternos\Mclogs\Id $id): ?string
    {
        self::Connect();

        return self::$connection->get($id->getRaw()) ?: null;
    }

    /**
     * Renew the data to reset the time to live
     *
     * @param \Aternos\Mclogs\Id $id
     * @return bool Success
     */
    public static function Renew(\Aternos\Mclogs\Id $id): bool
    {
        self::Connect();
        $config = \Aternos\Mclogs\Config::Get("storage");

        self::$connection->expire($id->getRaw(), $config['storageTime']);
        return true;
    }
}