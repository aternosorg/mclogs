<?php

namespace Aternos\Mclogs\Storage;

use Aternos\Mclogs\Client\RedisClient;
use Aternos\Mclogs\Id;

class RedisStorage extends Storage {
    /**
     * @inheritDoc
     */
    public function put(string $data): ?Id
    {
        $id = new Id(storageId: StorageId::REDIS);
        do {
            $id->generateNew();
        } while($this->get($id) !== null);

        RedisClient::getInstance()->getConnection()->setEx($id->getRaw(), $this->getStorageTime(), $data);
        return $id;
    }

    /**
     * @inheritDoc
     */
    public function get(Id $id): ?string
    {
        return RedisClient::getInstance()->getConnection()->get($id->getRaw()) ?: null;
    }

    /**
     * @inheritDoc
     */
    public function renew(Id $id): bool
    {
        return !!RedisClient::getInstance()->getConnection()->expire($id->getRaw(), $this->getStorageTime());
    }
}