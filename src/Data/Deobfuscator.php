<?php

namespace Aternos\Mclogs\Data;

use Aternos\Codex\Analysis\Information;
use Aternos\Codex\Log\AnalysableLog;
use Aternos\Codex\Log\LogInterface;
use Aternos\Codex\Minecraft\Analysis\Information\Vanilla\VanillaVersionInformation;
use Aternos\Codex\Minecraft\Log\Minecraft\Vanilla\Fabric\FabricLog;
use Aternos\Codex\Minecraft\Log\Minecraft\Vanilla\VanillaClientLog;
use Aternos\Codex\Minecraft\Log\Minecraft\Vanilla\VanillaCrashReportLog;
use Aternos\Codex\Minecraft\Log\Minecraft\Vanilla\VanillaLog;
use Aternos\Codex\Minecraft\Log\Minecraft\Vanilla\VanillaNetworkProtocolErrorReportLog;
use Aternos\Codex\Minecraft\Log\Minecraft\Vanilla\VanillaServerLog;
use Aternos\Mclogs\Cache\CacheEntry;
use Aternos\Sherlock\MapLocator\FabricMavenMapLocator;
use Aternos\Sherlock\MapLocator\LauncherMetaMapLocator;
use Aternos\Sherlock\Maps\GZURLYarnMap;
use Aternos\Sherlock\Maps\ObfuscationMap;
use Aternos\Sherlock\Maps\URLVanillaObfuscationMap;
use Aternos\Sherlock\Maps\VanillaObfuscationMap;
use Aternos\Sherlock\Maps\YarnMap;
use Aternos\Sherlock\ObfuscatedString;
use Exception;

class Deobfuscator
{
    public function __construct(protected LogInterface $codexLog)
    {
    }

    public function deobfuscate(): ?string
    {
        if (!$this->codexLog instanceof AnalysableLog) {
            return null;
        }
        if (!$this->codexLog instanceof VanillaLog) {
            return null;
        }
        $analysis = $this->codexLog->analyse();

        /**
         * @var ?Information $version
         */
        $version = $analysis->getFilteredInsights(VanillaVersionInformation::class)[0] ?? null;
        if (!$version) {
            return null;
        }
        $version = $version->getValue();

        try {
            $map = $this->getObfuscationMap($version);
        } catch (Exception) {
            $map = null;
        }

        if ($map === null) {
            return null;
        }

        $obfuscatedContent = new ObfuscatedString($this->codexLog->getLogFile()->getContent(), $map);
        if ($content = $obfuscatedContent->getMappedContent()) {
            return $content;
        }
        return null;
    }

    /**
     * Get the obfuscation map matching this log
     *
     * @param $version
     * @return ObfuscationMap|null
     * @throws Exception
     */
    protected function getObfuscationMap($version): ?ObfuscationMap
    {
        if (in_array(get_class($this->codexLog), [
            VanillaServerLog::class,
            VanillaClientLog::class,
            VanillaCrashReportLog::class,
            VanillaNetworkProtocolErrorReportLog::class
        ])) {
            $urlCache = new CacheEntry("sherlock:vanilla:$version:client");

            $mapURL = $urlCache->get();
            if (!$mapURL) {
                $mapURL = new LauncherMetaMapLocator($version, "client")->findMappingURL();

                if (!$mapURL) {
                    return null;
                }

                $urlCache->set($mapURL, 30 * 24 * 60 * 60);
            }

            try {
                $mapCache = new CacheEntry("sherlock:$mapURL");
                if ($mapContent = $mapCache->get()) {
                    $map = new VanillaObfuscationMap($mapContent);
                } else {
                    $map = new URLVanillaObfuscationMap($mapURL);
                    $mapCache->set($map->getContent());
                }
            } catch (Exception) {
            }
            return $map ?? null;
        }

        if ($this->codexLog instanceof FabricLog) {
            $urlCache = new CacheEntry("sherlock:yarn:$version:server");

            $mapURL = $urlCache->get();
            if (!$mapURL) {
                $mapURL = new FabricMavenMapLocator($version)->findMappingURL();

                if (!$mapURL) {
                    return null;
                }

                $urlCache->set($mapURL, 24 * 60 * 60);
            }

            try {
                $mapCache = new CacheEntry("sherlock:$mapURL");
                if ($mapContent = $mapCache->get()) {
                    $map = new YarnMap($mapContent);
                } else {
                    $map = new GZURLYarnMap($mapURL);
                    $mapCache->set($map->getContent());
                }
            } catch (Exception) {
            }
            return $map ?? null;
        }

        return null;
    }
}