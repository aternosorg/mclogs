<?php

$config = [

    /**
     * Available storages with ID, name and class
     *
     * The class should implement \Storage\StorageInterface
     */
    "storages" => [
        "m" => [
            "id" => "m",
            "name" => "MongoDB",
            "class" => "\\Storage\\Mongo"
        ],
        "f" => [
            "id" => "f",
            "name" => "Filesystem",
            "class" => "\\Storage\\Filesystem"
        ]
    ],

    /**
     * Current storage id for new data
     *
     * Should be a key in the $storages array
     */
    "storageId" => "m",

    /**
     * Time in seconds to store data after put or last renew
     */
    "storageTime" => 10 /* 3 * 24 * 60 * 60 */

];