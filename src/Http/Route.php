<?php
namespace Wonnova\SDK\Http;

/**
 * This class represents an immutable route, based on a route pattern, a list of params to be replaced on that pattern
 * and a list of query params
 *
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class Route
{
    /**
     * @var string
     */
    private $processedRoute;
    /**
     * @var string
     */
    private $routePattern;
    /**
     * @var array
     */
    private $routeParams;
    /**
     * @var array
     */
    private $queryParams;

    /**
     * @param string $routePattern
     * @param array $routeParams
     * @param array $queryParams
     */
    public function __construct($routePattern, array $routeParams = [], $queryParams = [])
    {
        $this->routePattern = $routePattern;
        $this->routeParams  = $routeParams;
        $this->queryParams  = $queryParams;
    }

    public function __toString()
    {
        if (! $this->isRouteProcesed()) {
            $this->processRoute();
        }

        return $this->processedRoute;
    }

    private function processRoute()
    {
        // Replace route params from route pattern
        $uri = $this->routePattern;
        foreach ($this->routeParams as $key => $value) {
            $key = '%' . $key . '%';
            $uri = str_replace($key, $value, $uri);
        }

        // If there are query params, process them
        if (! empty($this->queryParams)) {
            $uri = $uri . '?';
            foreach ($this->queryParams as $key => $value) {
                $uri .= sprintf('%s=%s&', $key, $value);
            }
            // Remove the last ampersand
            $uri = substr($uri, 0, strlen($uri) - 1);
        }

        $this->processedRoute = $uri;
    }

    private function isRouteProcesed()
    {
        return isset($this->processedRoute);
    }
}
