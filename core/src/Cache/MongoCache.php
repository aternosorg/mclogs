<?php

namespace Cache;

use Client\MongoDBClient;

class MongoCache extends MongoDBClient implements CacheInterface
{

    /**
     * @inheritDoc
     */
    public static function Set(string $key, string $value)
    {
        self::Connect();

        if (self::Exists($key)) {
            self::$collection->updateOne(["_id" => $key], ["data" => $value]);
        }
        else {
            self::$collection->insertOne(["_id" => $key, "data" => $value]);
        }
    }

    /**
     * @inheritDoc
     */
    public static function Get(string $key): ?string
    {
        self::Connect();

        return self::$collection->findOne(["_id" => $key])?->data;
    }

    /**
     * @inheritDoc
     */
    public static function Exists(string $key): bool
    {
        self::Connect();

        return self::$collection->findOne(["_id" => $key]) !== null;
    }
}