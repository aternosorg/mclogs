<?php

namespace Aternos\Mclogs\Filter;

class AccessTokenFilter extends Filter
{
    /**
     * @var array<string, string>
     */
    protected const array ACCESS_TOKEN_PATTERNS = [
        '/\(Session ID is token:[^:]+\:[^)]+\)/' => '(Session ID is token:****************:****************)',
        '/--accessToken [^ ]+/' => '--accessToken ****************:****************',
    ];

    /**
     * @inheritDoc
     */
    public function filter(string $data): string
    {
        foreach (static::ACCESS_TOKEN_PATTERNS as $pattern => $replacement) {
            $data = preg_replace($pattern, $replacement, $data);
        }
        return $data;
    }
}