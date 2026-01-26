<?php

namespace Aternos\Mclogs;

use Aternos\Codex\Analysis\Analysis;
use Aternos\Codex\Log\AnalysableLogInterface;
use Aternos\Codex\Log\File\StringLogFile;
use Aternos\Codex\Log\Level;
use Aternos\Codex\Log\LogInterface;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Data\Deobfuscator;
use Aternos\Mclogs\Data\MetadataEntry;
use Aternos\Mclogs\Data\Token;
use Aternos\Mclogs\Filter\Filter;
use Aternos\Mclogs\Printer\Printer;
use Aternos\Mclogs\Storage\MongoDBClient;
use Aternos\Mclogs\Util\URL;
use MongoDB\BSON\UTCDateTime;
use Uri\Rfc3986\Uri;

class Log
{
    protected const int SOURCE_MAX_LENGTH = 64;

    protected ?string $source = null;
    protected ?UTCDateTime $expires = null;
    protected ?UTCDateTime $created = null;
    protected ?Token $token = null;

    /**
     * @var MetadataEntry[]
     */
    protected array $metadata = [];

    protected ?LogInterface $log = null;
    protected ?Printer $printer = null;

    /**
     * Find a log by its id
     *
     * @param Id $id
     * @return static|null
     */
    public static function find(Id $id): ?static
    {
        $data = MongoDBClient::getInstance()->findLog($id);
        if ($data === null) {
            return null;
        }
        return new static($id)
            ->setContent($data->data ?? null)
            ->setToken($data->token ? new Token($data->token) : null)
            ->setMetadata(MetadataEntry::allFromObjectArray($data->metadata ?? []))
            ->setSource($data->source ?? null)
            ->setCreated($data->created ?? null)
            ->setExpires($data->expires ?? null);
    }

    /**
     * Create and save a new log
     *
     * @param string $content
     * @param MetadataEntry[] $metadata
     * @param string|null $source
     * @return static
     */
    public static function create(string $content, array $metadata = [], ?string $source = null): static
    {
        return new static()
            ->setMetadata($metadata)
            ->setSource($source)
            ->setToken(new Token())
            ->save($content);
    }

    /**
     * @param Id|null $id
     */
    public function __construct(protected ?Id $id = null)
    {
    }

    /**
     * @param Token|null $token
     * @return $this
     */
    public function setToken(?Token $token): static
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @param array $metadata
     * @return $this
     */
    public function setMetadata(array $metadata): static
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * @param MetadataEntry $metadataEntry
     * @return $this
     */
    public function addMetadata(MetadataEntry $metadataEntry): static
    {
        $this->metadata[] = $metadataEntry;
        return $this;
    }

    /**
     * @param string|null $source
     * @return $this
     */
    public function setSource(?string $source): static
    {
        if (is_string($source) && strlen($source) > static::SOURCE_MAX_LENGTH) {
            $source = substr($source, 0, static::SOURCE_MAX_LENGTH);
        }
        $this->source = $source;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * @param UTCDateTime|null $created
     * @return $this
     */
    public function setCreated(?UTCDateTime $created): static
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @param UTCDateTime|null $expires
     * @return $this
     */
    public function setExpires(?UTCDateTime $expires): static
    {
        $this->expires = $expires;
        return $this;
    }

    /**
     * @return UTCDateTime|null
     */
    public function getCreated(): ?UTCDateTime
    {
        return $this->created;
    }

    /**
     * @return UTCDateTime|null
     */
    public function getExpires(): ?UTCDateTime
    {
        return $this->expires;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): static
    {
        $this->processAndDeobfuscate($content);
        return $this;
    }

    public function getContent(): string
    {
        return $this->log->getLogFile()->getContent();
    }

    protected function processAndDeobfuscate(string $data): void
    {
        $this->process($data);
        $deobfuscator = new Deobfuscator($this->getCodexLog());
        if ($deobfuscatedData = $deobfuscator->deobfuscate()) {
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
    public function getLineCount(): int
    {
        $codexLog = $this->getCodexLog();
        $lines = 0;
        foreach ($codexLog as $entry) {
            $lines += count($entry);
        }
        return $lines;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return strlen($this->getContent());
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

    protected function generateId(): Id
    {
        do {
            $this->id = new Id();
        } while (MongoDBClient::getInstance()->hasLog($this->id));
        return $this->id;
    }

    /**
     * Save the log to the database
     *
     * @return $this
     */
    public function save(string $content): static
    {
        if ($this->id === null) {
            $this->generateId();
        }

        $content = Filter::filterAll($content);

        MongoDBClient::getInstance()->getLogsCollection()->insertOne([
            "_id" => $this->id->get(),
            "data" => $content,
            "token" => $this->token?->get(),
            "source" => $this->source,
            "metadata" => $this->metadata,
            "expires" => $this->expires = $this->getExpiryTimestamp(),
            "created" => $this->created = new UTCDateTime()
        ]);

        return $this;
    }

    /**
     * @return UTCDateTime
     */
    protected function getExpiryTimestamp(): UTCDateTime
    {
        $ttl = \Aternos\Mclogs\Config\Config::getInstance()->get(ConfigKey::STORAGE_TTL);
        $expires = time() + $ttl;
        return new UTCDateTime($expires * 1000);
    }

    /**
     * Renew the expiry timestamp to expand the ttl
     *
     * @return bool
     */
    public function renew(): bool
    {
        $expires = $this->getExpiryTimestamp();
        $result = MongoDBClient::getInstance()->setLogExpires($this->id, $expires);
        if ($result) {
            $this->expires = $expires;
        }
        return $result;
    }

    /**
     * @return Uri
     */
    public function getURL(): Uri
    {
        return URL::getBase()->withPath("/" . $this->id->get());
    }

    /**
     * @return Uri
     */
    public function getRawURL(): Uri
    {
        return URL::getApi()->withPath("/1/raw/" . $this->id->get());
    }

    /**
     * @return Id|null
     */
    public function getId(): ?Id
    {
        return $this->id;
    }

    /**
     * @return Token|null
     */
    public function getToken(): ?Token
    {
        return $this->token;
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        return MongoDBClient::getInstance()->getLogsCollection()
                ->deleteOne(['_id' => $this->id->get()])
                ->getDeletedCount() === 1;
    }

    /**
     * @return MetadataEntry[]
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
