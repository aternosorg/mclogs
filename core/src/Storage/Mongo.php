<?php

namespace Storage;

class Mongo implements StorageInterface
{
    /**
     * @var null|\MongoDB\Collection
     */
    private static $collection = null;

    /**
     * Connect to MongoDB
     */
    private static function Connect()
    {

        if (self::$collection === null) {
            $connection = new \MongoDB\Client();
            self::$collection = $connection->mclogs->logs;
        }
    }

    /**
     * Put some data in the storage, returns the (new) id for the data
     *
     * @param string $data
     * @return \Id|boolean ID or false
     */
    public static function Put(string $data): \Id
    {
        self::Connect();

        $config = \Config::Get("storage");
        $id = new \Id();
        $id->setStorage("m");

        do {
            $id->regenerate();
        } while (self::Get($id) !== false);

        $date = new \MongoDB\BSON\UTCDateTime((time() + $config['storageTime']) * 1000);

        self::$collection->insertOne([
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
     * @return string|false Data or false, e.g. if it doesn't exist
     */
    public static function Get(\Id $id)
    {
        self::Connect();

        $result = self::$collection->findOne(["_id" => $id->getRaw()]);

        if($result === null) {
            return false;
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
        self::Connect();

        $date = new \MongoDB\BSON\UTCDateTime((time() + $config['storageTime']) * 1000);

        self::$collection->updateOne(["_id" => $id->getRaw()], ['$set' => ['expires' => $date]]);

        return true;
    }
}