<?php

namespace Aternos\Mclogs\Frontend\Assets;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;

class Asset implements \JsonSerializable
{
    protected const string HASH_ALGORITHM = 'sha384';

    /**
     * @param object $data
     * @return static|null
     */
    public static function fromObject(object $data): ?static
    {
        if (!isset($data->type) || !isset($data->path) || !isset($data->hash)) {
            return null;
        }

        $type = AssetType::tryFrom($data->type);
        if ($type === null) {
            return null;
        }

        return new static($type, $data->path, $data->hash);
    }

    public function __construct(
        protected AssetType $type,
        protected string    $path,
        protected ?string   $hash = null)
    {
        $this->path = ltrim($this->path, '/');
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getPathWithVersion(): string
    {
        return $this->path . '?v=' . rawurlencode(substr($this->getHash(), 0, 16));
    }

    protected function getAbsoluteBasePath(): string
    {
        return __DIR__ . "/../../../web/public/";
    }

    protected function getAbsolutePath(): string
    {
        return $this->getAbsoluteBasePath() . $this->path;
    }

    protected function buildHash(): string
    {
        return hash_file(static::HASH_ALGORITHM, $this->getAbsolutePath());
    }

    protected function getHash(): string
    {
        if ($this->hash === null) {
            return $this->buildHash();
        }
        return $this->hash;
    }

    protected function getBase64Hash(): string
    {
        return base64_encode(hex2bin($this->getHash()));
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->getType()->value,
            'path' => $this->getPath(),
            'hash' => $this->getHash(),
        ];
    }

    public function getType(): AssetType
    {
        return $this->type;
    }

    public function getHTML(): string
    {
        return match ($this->type) {
            AssetType::CSS => '<link rel="stylesheet" href="/' . $this->getPathWithVersion() . '"' . $this->getIntegrityAttribute() . ' />',
            AssetType::JS => '<script src="/' . $this->getPathWithVersion() . '"' . $this->getIntegrityAttribute() . '></script>'
        };
    }

    protected function getIntegrityAttribute(): string
    {
        if (!Config::getInstance()->get(ConfigKey::FRONTEND_ASSETS_INTEGRITY)) {
            return '';
        }
        return ' integrity="' . static::HASH_ALGORITHM . '-' . $this->getBase64Hash() . '"';
    }
}