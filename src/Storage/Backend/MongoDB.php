<?php

namespace Aternos\Mclogs\Storage\Backend;

use Aternos\Mclogs\Client\MongoDBClient;
use MongoDB\Collection;

class MongoDB extends StorageBackend
{
    protected ?Collection $collection = null;

    protected function getCollection(): Collection
    {
        if ($this->collection === null) {
            $this->collection = MongoDBClient::getInstance()->getCollection($this->getName());
        }
        return $this->collection;
    }

    /**
     * @inheritDoc
     */
    public function put(string $id, string $data, ?int $ttl = null): bool
    {
        /** @noinspection PhpComposerExtensionStubsInspection */
        $date = new \MongoDB\BSON\UTCDateTime($this->getTTLExpiryTimestamp($ttl) * 1000);

        $this->getCollection()->insertOne([
            "_id" => $id,
            "expires" => $date,
            "data" => $data
        ]);

        return $id;
    }

    /**
     * @inheritDoc
     */
    public function get(string $id): ?string
    {
        $result = $this->getCollection()->findOne(["_id" => $id]);
        return $result?->data;
    }

    /**
     * @inheritDoc
     */
    public function renew(string $id, ?int $ttl = null): bool
    {
        /** @noinspection PhpComposerExtensionStubsInspection */
        $date = new \MongoDB\BSON\UTCDateTime($this->getTTLExpiryTimestamp($ttl) * 1000);

        $result = $this->getCollection()->updateOne(["_id" => $id], ['$set' => ['expires' => $date]]);
        return $result->getModifiedCount() === 1;
    }

    /**
     * @inheritDoc
     */
    public function getId(): StorageBackendId
    {
        return StorageBackendId::MONGODB;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id): bool
    {
        $result = $this->getCollection()->deleteOne(["_id" => $id]);
        return $result->isAcknowledged();
    }
}