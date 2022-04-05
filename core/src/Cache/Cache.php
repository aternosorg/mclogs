<?php

namespace Cache;

use Config;

class Cache
{
    protected ?CacheInterface $cache;

    public function __construct()
    {
        $config = Config::Get('cache');
        $this->cache = $config['cacheId'] ?? null;
    }

    /**
     * get a value
     * @param string $key
     * @return string|null
     */
    public function get(string $key): ?string
    {
        if (!$this->cache) {
            return null;
        }

        return $this->cache::Get($key);
    }

    /**
     * Is this key used
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        if (!$this->cache) {
            return false;
        }

        return $this->cache::Exists($key);
    }

    /**
     * set this cache value
     * @param string $key
     * @param string $value
     * @param int|null $duration cache time (in seconds) null means the value will be cached forever
     * @return void
     */
    public function set(string $key, string $value, ?int $duration = null)
    {
        if (!$this->cache) {
            return;
        }

        $this->cache::Set($key, $value);
    }
}