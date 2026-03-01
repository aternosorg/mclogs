<?php

namespace IndifferentKetchup\IBLogs\Filter;

use IndifferentKetchup\IBLogs\Filter\Pattern\PatternWithReplacement;

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