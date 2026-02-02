<?php

namespace Aternos\Mclogs\Filter;

use Aternos\Mclogs\Filter\Pattern\PatternWithReplacement;

class AccessTokenFilter extends RegexFilter
{
    /**
     * @inheritDoc
     */
    protected function getPatterns(): array
    {
        return [
            new PatternWithReplacement('\(Session ID is token:[^:]+\:[^)]+\)', '(Session ID is token:****************:****************)'),
            new PatternWithReplacement('--accessToken [^ ]+', '--accessToken ****************:****************'),
        ];
    }
}