<?php

namespace Storage;

class Redis implements StorageInterface {

    /**
     * @var \Redis
     */
    private static $connection = null;

    /**
     * Connect to redis
     */
    private static function Connect()
    {
        if(self::$connection === null) {
            self::$connection = new \Redis();
            self::$connection->connect('127.0.0.1', 6379);
        }
    }

    /**
     * Put some data in the storage, returns the (new) id for the data
     *
     * @param string $data
     * @return \Id|boolean ID or false
     */
    public static function Put(string $data): \Id
    {
        self::Connect();
        $config = \Config::Get("storage");

        $id = new \Id();
        $id->setStorage("r");

        do {
            $id->regenerate();
        } while(self::Get($id) !== false);

        self::$connection->setEx($id->getRaw(), $config['storageTime'], $data);
        return $id;
    }

    /**
     * Get some data from the storage by id
     *
     * @param \Id $id
     * @return string|false Data or false, e.g. if it doesn't exist
     */
    public static function Get(\Id $id)
    {
        self::Connect();

        return self::$connection->get($id->getRaw());
    }

    /**
     * Renew the data to reset the time to live
     *
     * @param \Id $id
     * @return bool Success
     */
    public static function Renew(\Id $id): bool
    {
        self::Connect();
        $config = \Config::Get("storage");

        self::$connection->setTimeout($id->getRaw(), $config['storageTime']);
        return true;
    }
}