<?php
namespace Wonnova\SDK\Common;

/**
 * Class URIUtils
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class URIUtils
{
    const HOST = 'https://secure.wonnova.com';

    /**
     * Parses certain URI with a list of route params
     *
     * @param string $uri
     * @param array $routeParams
     * @return string
     */
    public static function parseUri($uri, array $routeParams = [])
    {
        $uri = sprintf('/rest%s', $uri);

        foreach ($routeParams as $key => $value) {
            $key = '%' . $key . '%';
            $uri = str_replace($key, $value, $uri);
        }

        return $uri;
    }
}
