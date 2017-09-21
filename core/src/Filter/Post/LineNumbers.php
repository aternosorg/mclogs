<?php

namespace Filter\Post;

class LineNumbers implements PostFilterInterface
{

    /**
     * Filter the $data string, add some $meta data and return the string
     *
     * Adds line numbers to every line
     *
     * @param string $data
     * @param array $meta
     * @return string
     */
    public static function Filter(string $data, array &$meta): string
    {
        $lines = explode("\n", $data);

        $count = 0;
        foreach ($lines as $i => $line) {
            $count++;
            $lines[$i] = '<a href="/'.$meta['id']->get().'#L'.$count.'" id="L'.$count.'" class="line-number">'.$count.'</a>'.$line;
        }

        return implode("\n", $lines);
    }
}