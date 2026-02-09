<?php

namespace Aternos\Mclogs\Storage;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Util\Singleton;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;
use Uri\Rfc3986\Uri;

class MongoDBClient
{
    use Singleton;

    protected ?Client $connection = null;
    protected Database $database;

    protected ?Collection $logs = null;
    protected ?Collection $cache = null;

    /**
     * @return string
     */
    protected function getConnectionURL(): string
    {
        $configUrl = Config::getInstance()->get(ConfigKey::MONGODB_URL);
        $url = new Uri($configUrl);
        $query = $url->getQuery();
        $queryParams = [];
        if ($query !== null) {
            parse_str($query, $queryParams);
        }
        if (!isset($queryParams['serverSelectionTimeoutMS'])) {
            $queryParams['serverSelectionTimeoutMS'] = 5_000;
        }
        if (!isset($queryParams['socketTimeoutMS'])) {
            $queryParams['socketTimeoutMS'] = 60_000;
        }
        $newQuery = http_build_query($queryParams);
        $newUrl = $url->withQuery($newQuery);
        return $newUrl->toString();
    }

    /**
     * Connect to MongoDB
     */
    protected function connect(): void
    {
        if ($this->connection === null) {
            $config = Config::getInstance();
            $this->connection = new Client($this->getConnectionURL());
            $this->database = $this->connection->getDatabase($config->get(ConfigKey::MONGODB_DATABASE));
        }
    }

    /**
     * Ensure indexes exist
     *
     * @return void
     */
    public function ensureIndexes(): void
    {
        $logs = $this->getLogsCollection();
        $logs->createIndex(['expires' => 1], ['expireAfterSeconds' => 0]);

        $cache = $this->getCacheCollection();
        $cache->createIndex(['expires' => 1], ['expireAfterSeconds' => 0]);
    }

    /**
     * @return void
     */
    public function reset(): void
    {
        $this->connection = null;
        $this->logs = null;
        $this->cache = null;
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
     * @param bool $includeContent
     * @return object|null
     */
    public function findLog(string $id, bool $includeContent = true): ?object
    {
        $options = [];
        if (!$includeContent) {
            $options['projection'] = ['data' => 0];
        }

        $collection = $this->getLogsCollection();
        $result = $collection->findOne(['_id' => $id], $options);
        if ($result === null) {
            // Check for legacy ID without the first character
            return $collection->findOne(['_id' => substr($id, 1)], $options);
        }
        return $result;
    }

    /**
     * @param string[] $ids
     * @param bool $includeContent
     * @return object[]
     */
    public function findLogs(array $ids, bool $includeContent = true): array
    {
        $options = [];
        if (!$includeContent) {
            $options['projection'] = ['data' => 0];
        }

        $collection = $this->getLogsCollection();
        $results = $collection->find(['_id' => ['$in' => $ids]], $options)->toArray();
        $foundIds = [];
        foreach ($results as $result) {
            $foundIds[] = (string)$result->_id;
        }

        $missingIds = array_diff($ids, $foundIds);
        if (!empty($missingIds)) {
            $legacyIds = [];
            foreach ($missingIds as $id) {
                $legacyIds[substr($id, 1)] = $id;
            }

            // Check for legacy IDs without the first character
            $legacyResults = $collection->find(['_id' => ['$in' => array_keys($legacyIds)]], $options)->toArray();
            foreach ($legacyResults as $result) {
                // Map the legacy ID back to the original ID
                $originalId = $legacyIds[(string)$result->_id];
                $result->_id = $originalId;

                // Add the found legacy results to the main results array
                $results[] = $result;
            }
        }
        return $results;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function deleteLog(string $id): bool
    {
        $collection = $this->getLogsCollection();
        $result = $collection->deleteOne(['_id' => $id]);
        if ($result->getDeletedCount() === 0) {
            // Check for legacy ID without the first character
            $result = $collection->deleteOne(['_id' => substr($id, 1)]);
            return $result->getDeletedCount() === 1;
        }
        return true;
    }

    /**
     * @param array $ids
     * @return int Number of logs deleted
     */
    public function deleteLogs(array $ids): int
    {
        $collection = $this->getLogsCollection();
        $result = $collection->deleteMany(['_id' => ['$in' => $ids]]);
        $deletedCount = $result->getDeletedCount();

        if ($deletedCount === count($ids)) {
            return $deletedCount;
        }

        // Check for legacy IDs without the first character
        $legacyIds = [];
        foreach ($ids as $id) {
            $legacyIds[] = substr($id, 1);
        }
        $legacyResult = $collection->deleteMany(['_id' => ['$in' => $legacyIds]]);
        return $deletedCount + $legacyResult->getDeletedCount();
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
