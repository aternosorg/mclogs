<?php

namespace Filter\Post;

class LineBreaks implements PostFilterInterface
{

    /**
     * Filter the $data string, add some $meta data and return the string
     *
     * Replaces new lines with <br /> tags
     *
     * @param string $data
     * @param array $meta
     * @return string
     */
    public static function Filter(string $data, array &$meta): string
    {
        return nl2br($data);
    }
}