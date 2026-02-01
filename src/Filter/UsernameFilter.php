<?php

namespace Aternos\Mclogs\Filter;

class UsernameFilter extends RegexFilter
{
    /**
     * @inheritDoc
     */
    protected function getPatterns(): array
    {
        return [
            "C:\\\\Users\\\\([^\\\\]+)\\\\" => "C:\\Users\\********\\", // windows
            "C:\\\\\\\\Users\\\\\\\\([^\\\\]+)\\\\\\\\" => "C:\\\\Users\\\\********\\\\", // windows with double backslashes
            "C:\\/Users\\/([^\\/]+)\\/" => "C:/Users/********/", // windows with forward slashes
            "(?<!\\w)\\/home\\/[^\\/]+\\/" => "/home/********/", // linux
            "(?<!\\w)\\/Users\\/[^\\/]+\\/" => "/Users/********/", // macos
            "USERNAME=\\w+" => "USERNAME=********", // environment variable
        ];
    }
}