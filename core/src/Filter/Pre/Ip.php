<?php

namespace Filter\Pre;

class Ip implements PreFilterInterface
{
    protected const IPV6_PATTERN = '/(?<=^|\W)((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?(?=$|\W)/';
    protected const IPV4_PATTERN = '/(?<!version: )(?<!version )(?<!([0-9]|-|\w))([0-9]{1,3}\.){3}[0-9]{1,3}(?![0-9])/i';

    protected const IPV6_FILTER = [
        '/^[0:]+1?$/'
    ];

    protected const IPV4_FILTER = [
        '/^127\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/',
        '/^0\.0\.0\.0$/',
        '/^1\.[01]\.[01]\.1$/',
        '/^8\.8\.[84]\.[84]$/',
    ];

    /**
     * Filter the $data string and return it
     *
     * Searches for IP addresses and censors them
     * IPv4 + IPv6
     *
     * @param string $data
     * @return string
     */
    public static function Filter(string $data): string
    {
        // IPv6
        $data = preg_replace_callback(static::IPV6_PATTERN, function ($matches) {
            foreach (self::IPV6_FILTER as $filter) {
                if (preg_match($filter, $matches[0])) {
                    return $matches[0];
                }
            }
            return "****:****:****:****:****:****:****:****";
        }, $data);

        // IPv4
        $data = preg_replace_callback(static::IPV4_PATTERN, function($matches) {
            foreach (self::IPV4_FILTER as $filter) {
                if (preg_match($filter, $matches[0])) {
                    return $matches[0];
                }
            }
            return "**.**.**.**";
        }, $data);

        return $data;
    }
}