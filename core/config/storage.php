<?php

$config = [

    /**
     * Available storages with ID, name and class
     *
     * The class should implement \Storage\StorageInterface
     */
    "storages" => [
        "m" => [
            "name" => "MongoDB",
            "class" => "\\Storage\\Mongo"
        ],
        "f" => [
            "name" => "Filesystem",
            "class" => "\\Storage\\Filesystem"
        ],
        "r" => [
            "name" => "Redis",
            "class" => "\\Storage\\Redis"
        ]
    ],

    /**
     * Current storage id for new data
     *
     * Should be a key in the $storages array
     */
    "storageId" => "r",

    /**
     * Time in seconds to store data after put or last renew
     */
    "storageTime" => 3 * 24 * 60 * 60

];