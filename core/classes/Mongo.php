<?php

class MongoStorage implements Storage
{

    /**
     * @var null|MongoDB\Collection
     */
    private static $connection = null;

    /**
     *
     *
     * @return MongoDB\Collection
     */
    private static function Connect(): MongoDB\Collection
    {

        if (self::$connection === null) {
            self::$connection = new MongoDB\Client();
        }

        return self::$connection->mclogs->logs;
    }

    public static function Put(string $data): string!
    {
        // TODO: Implement Put() method.
    }

    public static function Get(string $id): string
    {
        // TODO: Implement Get() method.
    }

    public static function Renew(string $id): bool
    {
        // TODO: Implement Renew() method.
    }
}