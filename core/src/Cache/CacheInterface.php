<?php

namespace Cache;

interface CacheInterface
{
    /**
     * Set the cached value for this key
     * @param string $key
     * @param string $value
     */
    public static function Set(string $key, string $value);

    /**
     * Get the cached data
     * @param string $key
     * @return ?string
     */
    public static function Get(string $key): ?string;

    /**
     * Is this key used
     * @param string $key
     * @return bool
     */
    public static function Exists(string $key): bool;
}