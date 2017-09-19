<?php

namespace Filter\Post;

class Colors implements PostFilterInterface
{
    /**
     * Filter the $data string, add some $meta data and return the string
     *
     * Replaces console color codes with spans
     *
     * @param string $data
     * @param array $meta
     * @return string
     */
    public static function Filter(string $data, array &$meta): string
    {
        $colors = \Config::Get('colors');

        $search = array();
        $replace = array();
        foreach ($colors as $code => $color) {
            $search[] = "\e[" . $code;
            $replace[] = '<span class="format format-' . $color . '">';
        }

        $lines = explode("\n", $data);
        foreach ($lines as $i => $line) {
            $count = 0;
            $lines[$i] = str_replace($search, $replace, $line, $count);

            for ($j = 0; $j < $count; $j++) {
                $lines[$i] .= '</span>';
            }
        }
        $data = implode("\n", $lines);

        return $data;
    }
}