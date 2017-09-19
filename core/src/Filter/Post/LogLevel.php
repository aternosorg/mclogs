<?php

namespace Filter\Post;

class LogLevel implements PostFilterInterface
{

    /**
     * Filter the $data string, add some $meta data and return the string
     *
     * Detects loglevel and adds spans to highlight and colorize loglevels
     *
     * @param string $data
     * @param array $meta
     * @return string
     */
    public static function Filter(string $data, array &$meta): string
    {
        $data = preg_replace(
            '/(\[(?:[0-9]{2}\:?){3}\] \[.*\/(\w+)\]\:)/',
            '</span><span class="level level-$2"><span class="level-prefix">$1</span>',
            $data
        );

        $data = substr($data, strlen('</span>'));
        $data .= '</span>';

        return $data;
    }
}