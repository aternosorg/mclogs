<?php

namespace Cache;

use Client\RedisClient;

class RedisCache extends RedisClient implements CacheInterface
{

    /**
     * @inheritDoc
     */
    public static function Set(string $key, string $value, ?int $duration = null)
    {
        self::Connect();
        if ($duration) {
            self::$connection->setEx($key, $duration, $value);
        }
        else {
            self::$connection->set($key, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public static function Get(string $key): ?string
    {
        self::Connect();
        return self::$connection->get($key) ?: null;
    }

    /**
     * @inheritDoc
     */
    public static function Exists(string $key): bool
    {
        self::Connect();
        return (bool)self::$connection->exists($key);
    }
}