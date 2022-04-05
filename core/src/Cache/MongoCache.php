<?php

namespace Cache;

use Client\MongoDBClient;
use MongoDB\BSON\UTCDateTime;

class MongoCache extends MongoDBClient implements CacheInterface
{
    protected const COLLECTION_NAME = "cache";

    /**
     * @inheritDoc
     */
    public static function Set(string $key, string $value, ?int $duration = null)
    {
        self::Connect();

        $date = null;
        if ($duration) {
            $date = new UTCDateTime((time() + $duration) * 1000);
        }

        if (self::Exists($key)) {
            self::$collection->updateOne(["_id" => $key], ["data" => $value, "expires" => $date]);
        }
        else {
            self::$collection->insertOne(["_id" => $key, "data" => $value, "expires" => $date]);
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