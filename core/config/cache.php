<?php

$config = [

    /**
     * Available caches with ID, name and class
     *
     * The class should implement \Cache\CacheInterface
     */
    "caches" => [
        "m" => [
            "name" => "MongoDB",
            "class" => "\\Cache\\MongoCache"
        ],
        "r" => [
            "name" => "Redis",
            "class" => "\\Cache\\RedisCache"
        ]
    ],

    /**
     * Current cache id
     *
     * Should be a key in the $storages array
     */
    "cacheId" => "r"

];