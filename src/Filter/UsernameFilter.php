<?php

namespace Aternos\Mclogs\Filter;

use Aternos\Mclogs\Filter\Pattern\PatternWithReplacement;

class UsernameFilter extends RegexFilter
{
    /**
     * @inheritDoc
     */
    protected function getPatterns(): array
    {
        return [
            new PatternWithReplacement("C:\\\\Users\\\\([^\\\\]+)\\\\", "C:\\Users\\********\\"), // windows
            new PatternWithReplacement("C:\\\\\\\\Users\\\\\\\\([^\\\\]+)\\\\\\\\", "C:\\\\Users\\\\********\\\\"), // windows with double backslashes
            new PatternWithReplacement("C:\\/Users\\/([^\\/]+)\\/", "C:/Users/********/"), // windows with forward slashes
            new PatternWithReplacement("(?<!\\w)\\/home\\/[^\\/]+\\/", "/home/********/"), // linux
            new PatternWithReplacement("(?<!\\w)\\/Users\\/[^\\/]+\\/", "/Users/********/"), // macos
            new PatternWithReplacement("USERNAME=\\w+", "USERNAME=********"), // environment variable
        ];
    }
}