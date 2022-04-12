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
        $date = null;
        if ($duration) {
            $date = new UTCDateTime((time() + $duration) * 1000);
        }

        if (self::Exists($key)) {
            self::getCollection()->updateOne(["_id" => $key], ["data" => $value, "expires" => $date]);
        }
        else {
            self::getCollection()->insertOne(["_id" => $key, "data" => $value, "expires" => $date]);
        }
    }

    /**
     * @inheritDoc
     */
    public static function Get(string $key): ?string
    {
        return self::getCollection()->findOne(["_id" => $key])?->data;
    }

    /**
     * @inheritDoc
     */
    public static function Exists(string $key): bool
    {
        return self::getCollection()->findOne(["_id" => $key]) !== null;
    }
}