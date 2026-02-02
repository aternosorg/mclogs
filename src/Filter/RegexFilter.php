<?php

namespace Aternos\Mclogs\Filter;

use Aternos\Mclogs\Filter\Pattern\Pattern;
use Aternos\Mclogs\Filter\Pattern\PatternWithReplacement;

abstract class RegexFilter extends Filter
{
    /**
     * @return PatternWithReplacement[]
     */
    abstract protected function getPatterns(): array;

    /**
     * @return Pattern[]
     */
    protected function getExemptions(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getType(): FilterType
    {
        return FilterType::REGEX;
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return [
            "patterns" => $this->getPatterns(),
            "exemptions" => $this->getExemptions(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function filter(string $data): string
    {
        foreach ($this->getPatterns() as $pattern) {
            $data = preg_replace_callback($pattern->get(), function ($matches) use ($pattern) {
                foreach ($this->getExemptions() as $exemptionPattern) {
                    if (preg_match($exemptionPattern->get(), $matches[0])) {
                        return $matches[0];
                    }
                }
                return $pattern->getReplacement();
            }, $data);
        }
        return $data;
    }
}