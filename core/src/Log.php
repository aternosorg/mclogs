<?php

class Log
{

    /**
     * @var bool
     */
    private $exists = false;

    /**
     * @var \Id
     */
    private $id = null;

    /**
     * @var string
     */
    private $data = null;

    /**
     * Log constructor.
     *
     * @param Id|null $id
     */
    public function __construct(\Id $id = null)
    {
        if ($id) {
            $this->id = $id;
            $this->load();
        }
    }

    /**
     * Load the log from storage
     */
    private function load()
    {
        $config = Config::Get('storage');

        if (!isset($config['storages'][$this->id->getStorage()])) {
            $this->exists = false;
            return;
        }

        /**
         * @var \Storage\StorageInterface $storage
         */
        $storage = $config['storages'][$this->id->getStorage()]['class'];

        $data = $storage::Get($this->id);

        if ($data === false) {
            $this->exists = false;
            return;
        } else {
            $this->data = $data;
            $this->exists = true;
        }
    }

    /**
     * Checks if the log exists
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->exists;
    }

    /**
     * Get the log
     *
     * @return string
     */
    public function get()
    {
        return $this->data;
    }

    /**
     * Put data into the log
     *
     * @param string $data
     * @return bool|Id
     */
    public function put(string $data)
    {
        $config = Config::Get('storage');

        /**
         * @var \Storage\StorageInterface $storage
         */
        $storage = $config['storages'][$config['storageId']]['class'];

        $this->id = $storage::Put($data);
        $this->data = $data;
        $this->exists = true;

        return $this->id;
    }

    /**
     * Renew the expiry timestamp to expand the ttl
     *
     * @param Id $id
     */
    public function renew()
    {
        $config = Config::Get('storage');

        /**
         * @var \Storage\StorageInterface $storage
         */
        $storage = $config['storages'][$this->id->getStorage()]['class'];

        $storage::Renew($this->id);
    }
}