<?php

namespace Aternos\Mclogs\Storage;

use Aternos\Mclogs\Client\MongoDBClient;
use Aternos\Mclogs\Id;

class MongoDBStorage extends Storage
{
    /**
     * @inheritDoc
     */
    public function put(string $data): ?Id
    {
        $id = new Id(storageId: StorageId::MONGODB);
        do {
            $id->generateNew();
        } while ($this->get($id) !== null);

        /** @noinspection PhpComposerExtensionStubsInspection */
        $date = new \MongoDB\BSON\UTCDateTime($this->getStorageExpiryTimestamp() * 1000);

        MongoDBClient::getInstance()->getCollection()->insertOne([
            "_id" => $id->getRaw(),
            "expires" => $date,
            "data" => $data
        ]);

        return $id;
    }

    /**
     * @inheritDoc
     */
    public function get(Id $id): ?string
    {
        $result = MongoDBClient::getInstance()->getCollection()->findOne(["_id" => $id->getRaw()]);
        return $result?->data;
    }

    /**
     * @inheritDoc
     */
    public function renew(Id $id): bool
    {
        /** @noinspection PhpComposerExtensionStubsInspection */
        $date = new \MongoDB\BSON\UTCDateTime($this->getStorageExpiryTimestamp() * 1000);

        $result = MongoDBClient::getInstance()->getCollection()->updateOne(["_id" => $id->getRaw()], ['$set' => ['expires' => $date]]);
        return $result->getModifiedCount() === 1;
    }
}