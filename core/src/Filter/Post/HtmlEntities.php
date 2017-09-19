<?php

namespace Filter\Post;

class HtmlEntities implements PostFilterInterface
{

    /**
     * Filter the $data string, add some $meta data and return the string
     *
     * Replaces HTML entities, mainly tags
     *
     * @param string $data
     * @param array $meta
     * @return string
     */
    public static function Filter(string $data, array &$meta): string
    {
        return htmlentities($data);
    }
}