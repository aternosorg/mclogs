<?php

namespace Aternos\Mclogs\Client;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Util\Singleton;
use MongoDB\Client;
use MongoDB\Collection;

class MongoDBClient
{
    use Singleton;

    protected const string COLLECTION_NAME = "logs";

    protected ?Client $connection = null;
    protected Collection $collection;

    /**
     * Connect to MongoDB
     */
    protected function connect(): void
    {
        if ($this->connection === null) {
            $config = Config::getInstance();
            $this->connection = new Client($config->get(ConfigKey::MONGODB_URL));
            $this->collection = $this->connection->getCollection($config->get(ConfigKey::MONGODB_DATABASE), static::COLLECTION_NAME);
        }
    }

    /**
     * Get the collection specified by {{@link COLLECTION_NAME}}
     *
     * @return Collection
     */
    public function getCollection(): Collection
    {
        $this->connect();
        return $this->collection;
    }
}