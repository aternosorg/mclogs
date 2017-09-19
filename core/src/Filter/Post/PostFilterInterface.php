<?php

namespace Filter\Post;

interface PostFilterInterface
{
    /**
     * Filter the $data string, add some $meta data and return the string
     *
     * @param string $data
     * @param array $meta
     * @return string
     */
    public static function Filter(string $data, array &$meta): string;
}