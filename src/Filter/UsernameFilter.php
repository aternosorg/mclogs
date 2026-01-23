<?php

namespace Aternos\Mclogs\Filter;

class UsernameFilter extends Filter
{
    /**
     * @type array<string, string>
     */
    protected const array USERNAME_PATTERNS = [
        "/C:\\\\Users\\\\([^\\\\]+)\\\\/" => "C:\\Users\\********\\", // windows
        "/C:\\\\\\\\Users\\\\\\\\([^\\\\]+)\\\\\\\\/" => "C:\\\\Users\\\\********\\\\", // windows with double backslashes
        "/C:\\/Users\\/([^\\/]+)\\//" => "C:/Users/********/", // windows with forward slashes
        "/(?<!\\w)\\/home\\/[^\\/]+\\//" => "/home/********/", // linux
        "/(?<!\\w)\\/Users\\/[^\\/]+\\//" => "/Users/********/", // macos
        "/^USERNAME=.+$/m" => "USERNAME=********", // environment variable
    ];

    /**
     * Censor usernames in paths
     *
     * @param string $data
     * @return string
     */
    public function filter(string $data): string
    {
        foreach (static::USERNAME_PATTERNS as $pattern => $replacement) {
            $data = preg_replace($pattern, $replacement, $data);
        }
        return $data;
    }
}