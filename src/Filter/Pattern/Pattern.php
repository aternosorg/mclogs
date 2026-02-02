<?php

namespace Aternos\Mclogs\Filter\Pattern;

class Pattern implements \JsonSerializable
{
    protected const string DELIMITER = '/';

    /**
     * @param string $pattern
     * @param Modifier[] $modifiers
     */
    public function __construct(
        protected string $pattern,
        protected array  $modifiers = [Modifier::CASELESS]
    )
    {
    }

    /**
     * Get the full regex pattern with delimiters and modifiers
     *
     * @return string
     */
    public function get(): string
    {
        $modifiersString = '';
        foreach ($this->modifiers as $modifier) {
            $modifiersString .= $modifier->value;
        }
        return static::DELIMITER . $this->pattern . static::DELIMITER . $modifiersString;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getModifiers(): array
    {
        return $this->modifiers;
    }

    public function jsonSerialize(): array
    {
        return [
            'pattern' => $this->getPattern(),
            'modifiers' => $this->getModifiers()
        ];
    }
}