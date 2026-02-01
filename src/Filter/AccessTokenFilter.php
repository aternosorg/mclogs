<?php

namespace Aternos\Mclogs\Filter;

class AccessTokenFilter extends RegexFilter
{
    /**
     * @inheritDoc
     */
    protected function getPatterns(): array
    {
        return [
            '\(Session ID is token:[^:]+\:[^)]+\)' => '(Session ID is token:****************:****************)',
            '--accessToken [^ ]+' => '--accessToken ****************:****************',
        ];
    }
}