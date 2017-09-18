<?php

namespace Storage;

class Mongo implements StorageInterface
{
    /**
     * @var null|\MongoDB\Collection
     */
    private static $collection = null;

    /**
     *
     *
     * @return \MongoDB\Collection
     */
    private static function Connect(): \MongoDB\Collection
    {

        if (self::$collection === null) {
            $connection = new \MongoDB\Client();
            self::$collection = $connection->mclogs->logs;
        }

        return self::$collection;
    }

    public static function Put(string $data): \Id
    {
        self::Connect();

        $config = \Config::Get("storage");
        $id = new \Id();
        $id->setStorage("m");

        do {
            $id->regenerate();
            $result = self::$collection->findOne(["_id" => $id->getRaw()]);
        } while ($result !== null);

        $date = new \MongoDB\BSON\UTCDateTime((time() + $config['storageTime']) * 1000);

        self::$collection->insertOne([
            "_id" => $id->getRaw(),
            "expires" => $date,
            "data" => $data
        ]);

        return $id;
    }

    public static function Get(\Id $id)
    {
        self::Connect();

        $result = self::$collection->findOne(["_id" => $id->getRaw()]);

        if($result === null) {
            return false;
        }

        return $result->data;
    }

    public static function Renew(\Id $id): bool
    {
        $config = \Config::Get("storage");
        self::Connect();

        $date = new \MongoDB\BSON\UTCDateTime((time() + $config['storageTime']) * 1000);

        self::$collection->updateOne(["_id" => $id->getRaw()], ['$set' => ['expires' => $date]]);

        return true;
    }
}