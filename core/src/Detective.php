<?php

use Aternos\Codex\Minecraft\Log\Minecraft\MinecraftLog;

class Detective extends \Aternos\Codex\Detective\Detective
{
    protected string $defaultLogClass = MinecraftLog::class;

    public function __construct()
    {
        $this->addDetective(new \Aternos\Codex\Minecraft\Detective\Detective())
            ->addDetective(new \Aternos\Codex\Hytale\Detective\Detective());
    }
}