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
        $suggestions = self::LoadSuggestions();
        $removals = [];

        foreach ($suggestions as $suggestion) {
            foreach ($suggestion["patterns"] as $pattern) {
                // find errors
                preg_match_all($pattern, $meta["raw"], $matches);

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
                            $removals[] = $removeId;
                        }
                    }
                }
            }
        }

        foreach ($removals as $removal) {
            if (isset($meta["suggestions"][$removal])) {
                unset($meta["suggestions"][$removal]);
            }
        }

        return $data;
    }

    /**
     * Load suggestions from files based on configuration
     *
     * @return array
     */
    private static function LoadSuggestions()
    {
        $config = \Config::Get("suggestions");
        $suggestions = [];

        foreach ($config["enabled"] as $enabledSuggestionNamespace) {
            $path = CORE_PATH . "/suggestions/" . $enabledSuggestionNamespace;

            $suggestionFiles = scandir($path);
            foreach ($suggestionFiles as $suggestionFile) {

                // skip hidden files and ./..
                if (substr($suggestionFile, 0, 1) === ".") {
                    continue;
                }

                $content = file_get_contents($path . "/" . $suggestionFile);
                $suggestion = json_decode($content, true);
                $suggestions[] = $suggestion;
            }
        }

        return $suggestions;
    }
}