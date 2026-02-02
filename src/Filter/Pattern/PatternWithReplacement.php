<?php

namespace Aternos\Mclogs\Filter\Pattern;

class PatternWithReplacement extends Pattern
{
    public function __construct(string $pattern, protected string $replacement, array $modifiers = [Modifier::CASELESS])
    {
        parent::__construct($pattern, $modifiers);
    }

    public function getReplacement(): string
    {
        return $this->replacement;
    }

    public function jsonSerialize(): array
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'replacement' => $this->getReplacement()
            ]
        );
    }
}