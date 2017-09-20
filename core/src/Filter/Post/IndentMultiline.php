<?php

namespace Filter\Post;

class IndentMultiline implements PostFilterInterface
{

    /**
     * Filter the $data string, add some $meta data and return the string
     *
     * Detects multiline errors and adds a span to indent them
     *
     * @param string $data
     * @param array $meta
     * @return string
     */
    public static function Filter(string $data, array &$meta): string
    {
        $pattern = [
            '(?:\[(?:[0-9]{2}\:?){3}\] \[.*\/(?:\w+)\]\:)', // regular
            '(?:(?:[0-9]{2,4}-?){3} (?:[0-9]{2}\:?){3} \[(?:\w+)\])' // old
        ];

        foreach ($pattern as $p) {

            $search = '/('.$p.'.*\n)((?:(?!'.$p.').*\n)+)/m';

            $data = preg_replace(
                $search,
                '$1<span class="log-multiline">$2</span>',
                $data
            );
        }

        return $data;
    }
}