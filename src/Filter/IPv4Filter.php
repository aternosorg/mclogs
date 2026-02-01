<?php

namespace Aternos\Mclogs\Filter;

class IPv4Filter extends RegexFilter
{
    /**
     * @inheritDoc
     */
    protected function getPatterns(): array
    {
        return [
            '(?<!version:? )(?<!([0-9]|-|\w))([0-9]{1,3}\.){3}[0-9]{1,3}(?![0-9])' => '**.**.**.**',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getExemptions(): array
    {
        return [
            '^127\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$',
            '^0\.0\.0\.0$',
            '^1\.[01]\.[01]\.1$',
            '^8\.8\.[84]\.[84]$',
        ];
    }
}