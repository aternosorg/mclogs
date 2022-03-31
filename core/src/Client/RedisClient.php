<?php

namespace Client;

use Redis;

class RedisClient
{
    /**
     * @var ?Redis
     */
    protected static ?Redis $connection = null;

    /**
     * Connect to redis
     */
    protected static function Connect()
    {
        if(self::$connection === null) {
            self::$connection = new Redis();
            self::$connection->connect('127.0.0.1', 6379);
        }
    }
}