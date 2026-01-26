<?php

namespace Aternos\Mclogs\Cache;

use Aternos\Mclogs\Storage\MongoDBClient;
use MongoDB\BSON\UTCDateTime;

class CacheEntry
{
    public function __construct(protected string $key)
    {
    }

    /**
     * @return string|null
     */
    public function get(): ?string
    {
        $result = MongoDBClient::getInstance()->getCacheCollection()->findOne([
            "_id" => $this->key
        ]);
        return $result?->data;
    }

    /**
     * @param string $data
     * @param int $ttl
     * @return $this
     */
    public function set(string $data, int $ttl = 24 * 60 * 60): static
    {
        MongoDBClient::getInstance()->getCacheCollection()->updateOne(
            ["_id" => $this->key],
            ['$set' => [
                'data' => $data,
                'expires' => new UTCDateTime((time() + $ttl) * 1000)
            ]],
            ['upsert' => true]
        );
        return $this;
    }

}