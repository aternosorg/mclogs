<?php

namespace Aternos\Mclogs;

use Aternos\Codex\Analysis\Analysis;
use Aternos\Codex\Analysis\Information;
use Aternos\Codex\Log\AnalysableLogInterface;
use Aternos\Codex\Log\File\StringLogFile;
use Aternos\Codex\Log\Level;
use Aternos\Codex\Log\LogInterface;
use Aternos\Codex\Minecraft\Analysis\Information\Vanilla\VanillaVersionInformation;
use Aternos\Codex\Minecraft\Log\Minecraft\Vanilla\Fabric\FabricLog;
use Aternos\Codex\Minecraft\Log\Minecraft\Vanilla\VanillaClientLog;
use Aternos\Codex\Minecraft\Log\Minecraft\Vanilla\VanillaCrashReportLog;
use Aternos\Codex\Minecraft\Log\Minecraft\Vanilla\VanillaNetworkProtocolErrorReportLog;
use Aternos\Codex\Minecraft\Log\Minecraft\Vanilla\VanillaServerLog;
use Aternos\Mclogs\Printer\Printer;
use Aternos\Mclogs\Storage\Backend\StorageBackendInterface;
use Aternos\Mclogs\Storage\LogsStorage;
use Aternos\Sherlock\MapLocator\FabricMavenMapLocator;
use Aternos\Sherlock\MapLocator\LauncherMetaMapLocator;
use Aternos\Sherlock\Maps\GZURLYarnMap;
use Aternos\Sherlock\Maps\ObfuscationMap;
use Aternos\Sherlock\Maps\URLVanillaObfuscationMap;
use Aternos\Sherlock\Maps\VanillaObfuscationMap;
use Aternos\Sherlock\Maps\YarnMap;
use Aternos\Sherlock\ObfuscatedString;
use Exception;

class Log
{
    protected bool $exists = false;
    protected ?Id $id = null;
    protected ?ObfuscatedString $obfuscatedContent = null;
    protected ?LogsStorage $storage = null;

    protected ?LogInterface $log = null;
    protected ?Printer $printer = null;

    /**
     * Log constructor.
     *
     * @param Id|null $id
     */
    public function __construct(?Id $id = null)
    {
        if ($id) {
            $this->id = $id;
            $this->load();
        }
    }

    /**
     * Load the log from storage
     */
    protected function load(): void
    {
        if ($this->id->getStorageBackendId() === null) {
            return;
        }
        $storage = new LogsStorage()->setBackendId($this->id->getStorageBackendId());

        $data = $storage->getLog($this->id);
        if ($data === null) {
            return;
        }

        $this->exists = true;

        $this->processAndDeobfuscate($data);
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
        if (in_array(get_class($this->getCodexLog()), [
            VanillaServerLog::class,
            VanillaClientLog::class,
            VanillaCrashReportLog::class,
            VanillaNetworkProtocolErrorReportLog::class
        ])) {
            $urlCache = new CacheEntry("sherlock:vanilla:$version:client");

            $mapURL = $urlCache->get();
            if (!$mapURL) {
                $mapURL = (new LauncherMetaMapLocator($version, "client"))->findMappingURL();

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

        if ($this->getCodexLog() instanceof FabricLog) {
            $urlCache = new CacheEntry("sherlock:yarn:$version:server");

            $mapURL = $urlCache->get();
            if (!$mapURL) {
                $mapURL = (new FabricMavenMapLocator($version))->findMappingURL();

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

    /**
     * Deobfuscate the content of this log
     *
     * @param LogInterface $codexLog
     * @return string|null
     */
    protected function deobfuscateContent(LogInterface $codexLog): ?string
    {
        $analysis = $this->getAnalysis();
        if ($analysis === null) {
            return null;
        }

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

        $this->obfuscatedContent = new ObfuscatedString($codexLog->getLogFile()->getContent(), $map);
        if ($content = $this->obfuscatedContent->getMappedContent()) {
            return $content;
        }
        return null;
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

    protected function processAndDeobfuscate(string $data): void
    {
        $this->process($data);
        if ($deobfuscatedData = $this->deobfuscateContent($this->log)) {
            $this->process($deobfuscatedData);
        }
    }

    protected function process($data): void
    {
        $this->log = new Detective()->setLogFile(new StringLogFile($data))->detect();
        $this->log->parse();
        if ($this->log instanceof AnalysableLogInterface) {
            $this->log->analyse();
        }
    }

    /**
     * Get the codex log object
     *
     * @return LogInterface
     */
    public function getCodexLog(): LogInterface
    {
        return $this->log;
    }

    /**
     * Get the log analysis
     *
     * @return Analysis|null
     */
    public function getAnalysis(): ?Analysis
    {
        $log = $this->getCodexLog();
        if ($log instanceof AnalysableLogInterface) {
            return $log->analyse();
        }
        return null;
    }

    /**
     * @return Printer
     */
    public function getPrinter(): Printer
    {
        if ($this->printer === null) {
            $this->printer = new Printer()->setLog($this->log)->setId($this->id);
        }
        return $this->printer;
    }

    /**
     * Get the amount of lines in this log
     *
     * @return int
     */
    public function getLineNumbers(): int
    {
        $codexLog = $this->getCodexLog();
        $lines = 0;
        foreach ($codexLog as $entry) {
            $lines += count($entry);
        }
        return $lines;
    }

    /**
     * Get the amount of error entries in the log
     *
     * @return int
     */
    public function getErrorCount(): int
    {
        $errorCount = 0;

        foreach ($this->log as $entry) {
            if ($entry->getLevel()->asInt() <= Level::ERROR->asInt()) {
                $errorCount++;
            }
        }

        return $errorCount;
    }

    /**
     * Set the data of the log without saving it to storage
     *
     * @param string $data
     * @return Log
     */
    public function setData(string $data): Log
    {
        $this->data = $data;
        $this->preFilter();
        return $this;
    }

    /**
     * Put data into the log
     *
     * @param string $data
     * @return ?Id
     */
    public function put(string $data): ?Id
    {
        $this->data = $data;
        $this->preFilter();

        $config = Config::Get('storage');

        /**
         * @var StorageBackendInterface $storage
         */
        $storage = $config['storages'][$config['storageId']]['class'];

        $this->id = $storage::Put($this->data);
        $this->exists = true;

        return $this->id;
    }

    /**
     * Renew the expiry timestamp to expand the ttl
     */
    public function renew()
    {
        $config = Config::Get('storage');

        /**
         * @var StorageBackendInterface $storage
         */
        $storage = $config['storages'][$this->id->getStorageBackendId()]['class'];

        $storage::Renew($this->id);
    }

    /**
     * Apply pre filters to data string
     */
    private function preFilter()
    {
        $config = Config::Get('filter');
        foreach ($config['pre'] as $preFilterClass) {
            /**
             * @var \Aternos\Mclogs\Filter\Filter $preFilterClass
             */

            $this->data = $preFilterClass::Filter($this->data);
        }
    }
}
