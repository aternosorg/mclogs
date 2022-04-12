<?php

namespace Storage;

use MongoDB\BSON\UTCDateTime;

class Mongo extends \Client\MongoDBClient implements StorageInterface
{
    protected const COLLECTION_NAME = "logs";

    /**
     * Put some data in the storage, returns the (new) id for the data
     *
     * @param string $data
     * @return ?\Id ID or false
     */
    public static function Put(string $data): ?\Id
    {
        $config = \Config::Get("storage");
        $id = new \Id();
        $id->setStorage("m");

        do {
            $id->regenerate();
        } while (self::Get($id) !== null);

        $date = new UTCDateTime((time() + $config['storageTime']) * 1000);

        self::getCollection()->insertOne([
            "_id" => $id->getRaw(),
            "expires" => $date,
            "data" => $data
        ]);

        return $id;
    }

    /**
     * Get some data from the storage by id
     *
     * @param \Id $id
     * @return ?string Data or null, e.g. if it doesn't exist
     */
    public static function Get(\Id $id): ?string
    {
        $result = self::getCollection()->findOne(["_id" => $id->getRaw()]);

        if ($result === null) {
            return null;
        }

        return $result->data;
    }

    /**
     * Renew the data to reset the time to live
     *
     * @param \Id $id
     * @return bool Success
     */
    public static function Renew(\Id $id): bool
    {
        $config = \Config::Get("storage");
        $date = new UTCDateTime((time() + $config['storageTime']) * 1000);

        self::getCollection()->updateOne(["_id" => $id->getRaw()], ['$set' => ['expires' => $date]]);

        return true;
    }
}