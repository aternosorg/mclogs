<?php

namespace Filter\Pre;

class AccessToken implements PreFilterInterface
{
    protected const ACCESS_TOKEN_PATTERNS = [
        '/\(Session ID is token:[^:]+\:[^)]+\)/' => '(Session ID is token:****************:****************)',
        '/--accessToken [^ ]+/' => '--accessToken ****************:****************',
    ];

    /**
     * @inheritDoc
     */
    public static function Filter(string $data): string
    {
        foreach (static::ACCESS_TOKEN_PATTERNS as $pattern => $replacement) {
            $data = preg_replace($pattern, $replacement, $data);
        }
        return $data;
    }
}