<?php

namespace Client;

use MongoDB\Client;
use MongoDB\Collection;

class MongoDBClient
{
    /**
     * MongoDB Collection name
     */
    protected const COLLECTION_NAME = "logs";

    /**
     * @var null|Client
     */
    protected static ?Client $connection = null;

    /**
     * Connect to MongoDB
     */
    protected static function Connect()
    {
        if (self::$collection === null) {
            $config = \Config::Get("mongo");
            $connection = new Client($config['url'] ?? 'mongodb://127.0.0.1/');
        }
    }

    /**
     * get the collection specified by {{@link COLLECTION_NAME}}
     * @return Collection
     */
    protected static function getCollection(): Collection
    {
        return self::$connection->mclogs->{static::COLLECTION_NAME};
    }
}