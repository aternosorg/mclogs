<?php

namespace Filter\Pre;

class Lines implements PreFilterInterface {

    /**
     * Filter the $data string and return it
     *
     * Cuts the lines down to maxLines
     *
     * @param string $data
     * @return string
     */
    public static function Filter(string $data): string
    {
        $config = \Config::Get('storage');
        $limit = $config["maxLines"];

        $lines = explode("\n", $data);
        $count = count($lines);

        if ($count <= $limit) {
            return $data;
        }

        $removed = $count - $limit + 3;
        $message = "Truncated " . $removed . " line" . ($removed > 1 ? "s" : "");

        array_splice($lines, $limit / 2, $removed, [
            str_repeat("=", strlen($message)),
            $message,
            str_repeat("=", strlen($message)),
        ]);

        return implode("\n", $lines);
    }
}