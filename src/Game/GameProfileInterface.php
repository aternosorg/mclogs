<?php

namespace IndifferentKetchup\IBLogs\Game;

use IndifferentKetchup\Codex\Detective\DetectorInterface;
use IndifferentKetchup\Codex\Analyser\AnalyserInterface;
use IndifferentKetchup\Codex\Parser\ParserInterface;

interface GameProfileInterface
{
    public function getId(): string;
    public function getName(): string;
    /** @return DetectorInterface[] */
    public function getDetectors(): array;
    public function getParser(): ?ParserInterface;
    public function getAnalyser(): ?AnalyserInterface;
    /** @return array<string, mixed> */
    public function getRulePack(): array;
}
