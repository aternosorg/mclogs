<?php

namespace Cache;

interface CacheInterface
{
    /**
     * Set the cached value for this key
     * @param string $key
     * @param string $value
     * @param int|null $duration cache time (in seconds) null means the value will be cached forever
     */
    public static function Set(string $key, string $value, ?int $duration = null);

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