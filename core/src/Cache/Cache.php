<?php

namespace Cache;

use Config;

class Cache
{
    protected ?CacheInterface $cache = null;

    public function __construct()
    {
        $config = Config::Get('cache');
        if (isset($config['cacheId'])) {
            $this->cache = new $config['cacheId']();
        }
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

    /**
     * get this value from the cache or generate it if it doesn't exist
     * @param string $key cache key
     * @param callable $generate function to generate value
     * @param int|null $duration cache duration
     * @param mixed ...$args arguments passed to the generate function
     * @return string
     */
    public function getOrGenerateAndSet(string $key, callable $generate, ?int $duration = null, ...$args): string
    {
        if ($result = $this->get($key)) {
            return $result;
        }
        else {
            $data = $generate(...$args);
            $this->set($key, $data, $duration);
            return $data;
        }
    }
}