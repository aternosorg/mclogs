<?php

namespace Aternos\Mclogs\Storage\Backend;

use Aternos\Mclogs\Client\RedisClient;

class Redis extends StorageBackend {

    /**
     * @param string $id
     * @return string
     */
    protected function getKey(string $id): string
    {
        return $this->getName() . '::' . $id;
    }

    /**
     * @inheritDoc
     */
    public function put(string $id, string $data, ?int $ttl = null): bool
    {
        RedisClient::getInstance()->getConnection()->setEx($this->getKey($id), $this->getTTL($ttl), $data);
        return $id;
    }

    /**
     * @inheritDoc
     */
    public function get(string $id): ?string
    {
        return RedisClient::getInstance()->getConnection()->get($this->getKey($id)) ?: null;
    }

    /**
     * @inheritDoc
     */
    public function renew(string $id, ?int $ttl = null): bool
    {
        return !!RedisClient::getInstance()->getConnection()->expire($this->getKey($id), $this->getTTL($ttl));
    }

    /**
     * @inheritDoc
     */
    public function getId(): StorageBackendId
    {
        return StorageBackendId::REDIS;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id): bool
    {
        return RedisClient::getInstance()->getConnection()->del($this->getKey($id)) > 0;
    }
}