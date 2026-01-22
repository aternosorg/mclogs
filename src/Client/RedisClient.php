<?php

namespace Aternos\Mclogs\Client;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Util\Singleton;
use Redis;

class RedisClient
{
    use Singleton;

    /**
     * @var ?Redis
     */
    protected ?Redis $connection = null;

    protected function connect(): void
    {
        if($this->connection === null) {
            $config = Config::getInstance();
            $this->connection = new Redis();
            $this->connection->connect($config->get(ConfigKey::REDIS_HOST), $config->get(ConfigKey::REDIS_PORT));
        }
    }

    /**
     * Get Redis connection
     *
     * @return Redis|null
     */
    public function getConnection(): ?Redis
    {
        return $this->connection;
    }
}