<?php

namespace Aternos\Mclogs\Frontend\Assets;

use Aternos\Mclogs\Util\Singleton;

class AssetLoader
{
    use Singleton;

    protected const string CACHE_PATH = __DIR__ . "/../../../assets.cache";

    /**
     * @var Asset[]
     */
    protected array $cachedAssets = [];

    protected function __construct()
    {
        $this->loadCache();
    }

    /**
     * @param AssetType $assetType
     * @param string $path
     * @return string
     */
    public function getHTML(AssetType $assetType, string $path): string
    {
        return $this->getAsset($assetType, $path)->getHTML();
    }

    /**
     * @param AssetType $assetType
     * @param string $path
     * @return Asset
     */
    protected function getAsset(AssetType $assetType, string $path): Asset
    {
        $cachedAsset = $this->findCachedAsset($assetType, $path);
        if ($cachedAsset !== null) {
            return $cachedAsset;
        }
        return new Asset($assetType, $path);
    }

    /**
     * @param AssetType $assetType
     * @param string $path
     * @return Asset|null
     */
    protected function findCachedAsset(AssetType $assetType, string $path): ?Asset
    {
        foreach ($this->cachedAssets as $asset) {
            if ($asset->getPath() === $path && $asset->getType() === $assetType) {
                return $asset;
            }
        }
        return null;
    }

    protected function loadCache(): void
    {
        if (!file_exists(self::CACHE_PATH)) {
            return;
        }

        $content = file_get_contents(self::CACHE_PATH);
        if ($content === false) {
            return;
        }

        $data = json_decode($content);
        if (!is_array($data)) {
            return;
        }

        foreach ($data as $assetData) {
            if (!is_object($assetData)) {
                continue;
            }
            $asset = Asset::fromObject($assetData);
            if ($asset === null) {
                continue;
            }
            $this->cachedAssets[] = $asset;
        }
    }

    public function writeCache(): void
    {
        $assets = [
            new Asset(AssetType::CSS, "css/mclogs.css"),
            new Asset(AssetType::JS, "js/start.js"),
            new Asset(AssetType::JS, "js/log.js"),
            new Asset(AssetType::CSS, "vendor/fontawesome/css/fontawesome.min.css")
        ];

        file_put_contents(static::CACHE_PATH, json_encode($assets));
    }
}