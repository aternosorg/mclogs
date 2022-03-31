<?php

namespace Cache;

use Client\RedisClient;

class RedisCache extends RedisClient implements CacheInterface
{

    /**
     * Set the cached value for this key
     * @param string $key
     * @param string $value
     */
    public static function Set(string $key, string $value)
    {
        self::Connect();
        self::$connection->set($key, $value);
    }

    /**
     * Get the cached data
     * @param string $key
     * @return string
     */
    public static function Get(string $key): ?string
    {
        self::Connect();
        return self::$connection->get($key) ?: null;
    }

    /**
     * Is this key used
     * @param string $key
     * @return bool
     */
    public static function Exists(string $key): bool
    {
        self::Connect();
        return (bool)self::$connection->exists($key);
    }
}