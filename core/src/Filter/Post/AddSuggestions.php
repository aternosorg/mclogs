<?php

namespace Filter\Post;

class AddSuggestions implements PostFilterInterface
{

    /**
     * Filter the $data string, add some $meta data and return the string
     *
     * Find errors and suggest solutions
     *
     * @param string $data
     * @param array $meta
     * @return string
     */
    public static function Filter(string $data, array &$meta): string
    {
        $suggestions = \Config::Get("suggestions");

        foreach ($suggestions as $suggestion) {

            // find errors
            preg_match_all($suggestion["pattern"], $meta["raw"], $matches);

            // restructure matches to make it more logical
            $logicalMatches = [];
            foreach ($matches as $i => $match) {
                foreach ($match as $j => $info) {
                    if (!is_array($logicalMatches[$j])) {
                        $logicalMatches[$j] = [];
                    }

                    $logicalMatches[$j][$i] = $info;
                }
            }

            foreach ($logicalMatches as $match) {
                $error = $match[0];

                // remove main match
                unset($match[0]);

                // insert interesting data into id and answer
                $id = vsprintf($suggestion["id"], $match);

                if (isset($meta["suggestions"][$id])) {
                    continue;
                }

                $answer = vsprintf($suggestion["answer"], $match);
                $meta["suggestions"][$id] = [
                    "answer" => $answer,
                    "error" => $error
                ];

                if (isset($suggestion["remove"])) {
                    foreach ($suggestion["remove"] as $remove) {
                        $removeId = vsprintf($remove, $match);
                        if (isset($meta["suggestions"][$removeId])) {
                            unset($meta["suggestions"][$removeId]);
                        }
                    }
                }
            }
        }

        return $data;
    }
}