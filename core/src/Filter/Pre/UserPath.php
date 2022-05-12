<?php

namespace Filter\Pre;

class UserPath implements PreFilterInterface
{
    protected const USER_PATH_PATTERNS = [
        "/C:\\\\Users\\\\([^\\\\]+)\\\\/" => "C:\\Users\\********\\", // windows
        "/C:\\/Users\\/([^\\/]+)\\//" => "C:/Users/********/", // windows with forward slashes
        "/(?<!\\w)\\/home\\/[^\\/]+\\//" => "/home/********/", // linux
        "/(?<!\\w)\\/Users\\/[^\\/]+\\//" => "/Users/********/" // macos
    ];

    /**
     * Censor usernames in paths
     *
     * @param string $data
     * @return string
     */
    public static function Filter(string $data): string
    {
        foreach (static::USER_PATH_PATTERNS as $pattern => $replacement) {
            error_log($pattern . " => " . $replacement);
            $data = preg_replace($pattern, $replacement, $data);
        }
        return $data;
    }
}