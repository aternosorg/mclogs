<?php

namespace Cache;

use Config;

class CacheEntry
{
    protected ?CacheInterface $cache = null;
    protected string $key;

    /**
     * @param string $key cache key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
        $config = Config::Get('cache');
        if (isset($config['cacheId'])) {
            $this->cache = new $config['cacheId']();
        }
    }

    /**
     * get the value of this entry
     * @return string|null
     */
    public function get(): ?string
    {
        if (!$this->cache) {
            return null;
        }

        return $this->cache::Get($this->key);
    }

    /**
     * Does this entry exist
     * @return bool
     */
    public function exists(): bool
    {
        if (!$this->cache) {
            return false;
        }

        return $this->cache::Exists($this->key);
    }

    /**
     * Set the value of this entry
     * @param string $value
     * @param int|null $duration cache time (in seconds) null means the value will be cached forever
     * @return void
     */
    public function set(string $value, ?int $duration = null)
    {
        if (!$this->cache) {
            return;
        }

        $this->cache::Set($this->key, $value, $duration);
    }
}