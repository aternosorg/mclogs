<?php

namespace Aternos\Mclogs\Filter;

use Aternos\Mclogs\Filter\Pattern\Pattern;
use Aternos\Mclogs\Filter\Pattern\PatternWithReplacement;

class IPv4Filter extends RegexFilter
{
    /**
     * @inheritDoc
     */
    protected function getPatterns(): array
    {
        return [
            new PatternWithReplacement('(?<!version:? )(?<!([0-9]|-|\w))([0-9]{1,3}\.){3}[0-9]{1,3}(?![0-9])', '**.**.**.**'),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getExemptions(): array
    {
        return [
            new Pattern('^127\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$'),
            new Pattern('^0\.0\.0\.0$'),
            new Pattern('^1\.[01]\.[01]\.1$'),
            new Pattern('^8\.8\.[84]\.[84]$'),
        ];
    }
}