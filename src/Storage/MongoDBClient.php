<?php

namespace Aternos\Mclogs\Storage;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Util\Singleton;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;

class MongoDBClient
{
    use Singleton;

    protected ?Client $connection = null;
    protected Database $database;

    protected ?Collection $logs = null;
    protected ?Collection $cache = null;

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
     * Get the collection for logs
     *
     * @return Collection
     */
    public function getLogsCollection(): Collection
    {
        if ($this->logs === null) {
            $this->connect();
            $this->logs = $this->database->getCollection('logs');
        }
        return $this->logs;
    }

    /**
     * @param string $id
     * @return object|null
     */
    public function findLog(string $id): ?object
    {
        $collection = $this->getLogsCollection();
        $result = $collection->findOne(['_id' => $id]);
        if ($result === null) {
            // Check for legacy ID without the first character
            return $collection->findOne(['_id' => substr($id, 1)]);
        }
        return $result;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function hasLog(string $id): bool
    {
        return $this->findLog($id) !== null;
    }

    /**
     * @param string $id
     * @param UTCDateTime $expires
     * @return bool
     */
    public function setLogExpires(string $id, UTCDateTime $expires): bool
    {
        $collection = $this->getLogsCollection();
        $result = $collection->updateOne(
            ['_id' => $id],
            ['$set' => ['expires' => $expires]]
        );
        return $result->getModifiedCount() === 1;
    }

    /**
     * Get the collection for caching
     *
     * @return Collection
     */
    public function getCacheCollection(): Collection
    {
        if ($this->cache === null) {
            $this->connect();
            $this->cache = $this->database->getCollection('cache');
        }
        return $this->cache;
    }
}