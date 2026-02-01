<?php

namespace Aternos\Mclogs\Filter;

abstract class RegexFilter extends Filter
{
    /**
     * @return array<string, string>
     */
    abstract protected function getPatterns(): array;

    /**
     * @return string[]
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
        foreach ($this->getPatterns() as $pattern => $replacement) {
            $pattern = '/' . $pattern . '/i';
            $data = preg_replace_callback($pattern, function ($matches) use ($replacement) {
                foreach ($this->getExemptions() as $exemption) {
                    $exemption = '/' .  $exemption . '/i';
                    if (preg_match($exemption, $matches[0])) {
                        return $matches[0];
                    }
                }
                return $replacement;
            }, $data);
        }
        return $data;
    }
}