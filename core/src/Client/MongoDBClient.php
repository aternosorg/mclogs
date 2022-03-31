<?php

namespace Client;

use MongoDB\Collection;

class MongoDBClient
{
    /**
     * @var null|Collection
     */
    protected static ?Collection $collection = null;

    /**
     * Connect to MongoDB
     */
    protected static function Connect()
    {
        if (self::$collection === null) {
            $config = \Config::Get("mongo");
            $connection = new \MongoDB\Client($config['url'] ?? 'mongodb://127.0.0.1/');
            self::$collection = $connection->mclogs->logs;
        }
    }
}