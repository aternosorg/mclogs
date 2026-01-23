<?php

namespace Aternos\Mclogs\Client;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Util\Singleton;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;

class MongoDBClient
{
    use Singleton;

    protected const string COLLECTION_NAME = "logs";

    protected ?Client $connection = null;
    protected Database $database;

    /**
     * Connect to MongoDB
     */
    protected function connect(): void
    {
        if ($this->connection === null) {
            $config = Config::getInstance();
            $this->connection = new Client($config->get(ConfigKey::MONGODB_URL));
            $this->database = $this->connection->getDatabase($config->get(ConfigKey::MONGODB_DATABASE));
        }
    }

    /**
     * Get a collection
     *
     * @param string $name
     * @return Collection
     */
    public function getCollection(string $name): Collection
    {
        $this->connect();
        return $this->database->getCollection($name);
    }
}