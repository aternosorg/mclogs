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
        $pattern = [
            '/(\[(?:[0-9]{2}\:?){3}\] \[[^\/]+\/(\w+)\](?: \[[^\]]+\])?\:)/', // regular
            '/((?:[0-9]{2,4}-?){3} (?:[0-9]{2}\:?){3} \[(\w+)\])/', // old,
            '/((?:[0-9]{2,4}\/?){3} (?:[0-9]{2}\:?){3} \[(\w+)\])/' // glowstone
        ];

        $count = 0;

        foreach ($pattern as $p) {
            $curCount = 0;

            $data = preg_replace(
                $p,
                '</span><span class="level level-$2"><span class="level-prefix">$1</span>',
                $data,
                -1,
                $curCount
            );

            $count += $curCount;
        }

        if($count > 0) {
            $data .= '</span>';
        }
        return $data;
    }
}