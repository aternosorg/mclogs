<?php

namespace Aternos\Mclogs\Filter\Pattern;

/**
 * https://www.php.net/manual/en/reference.pcre.pattern.modifiers.php
 */
enum Modifier: string implements \JsonSerializable
{
    case CASELESS = 'i';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}