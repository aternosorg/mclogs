<?php

namespace Filter\Pre;

class SessionId implements PreFilterInterface
{
    /**
     * @inheritDoc
     */
    public static function Filter(string $data): string
    {
        return preg_replace('/\(Session ID is token:[^:]+\:[^)]+\)/', '(Session ID is token:****************:****************)', $data);
    }
}