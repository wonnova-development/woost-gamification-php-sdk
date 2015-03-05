<?php
namespace Wonnova\SDK\Test\Http;

use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Http\Route;

/**
 * Class RouteTest
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class RouteTest extends TestCase
{
    public function testSimpleRoute()
    {
        $routePattern = '/foo/bar';
        $route = new Route($routePattern);
        $this->assertEquals($routePattern, $route->__toString());
    }

    public function testRouteWithRouteParams()
    {
        $routePattern = '/foo/%userId%/bar/%anotherParam%';
        $route = new Route($routePattern, [
            'userId' => '12345',
            'anotherParam' => 'value'
        ]);
        $this->assertEquals('/foo/12345/bar/value', $route->__toString());
    }

    public function testRouteWithQueryParams()
    {
        $routePattern = '/foo/bar';
        $route = new Route($routePattern, [], [
            'foo' => 1,
            'bar' => 2,
            'name' => 'wonnova'
        ]);
        $this->assertEquals('/foo/bar?foo=1&bar=2&name=wonnova', $route->__toString());
    }

    public function testRouteWithAllParams()
    {
        $routePattern = '/foo/%userId%/bar/%anotherParam%';
        $route = new Route($routePattern, [
            'userId' => '12345',
            'anotherParam' => 'value'
        ], [
            'foo' => 1,
            'bar' => 2
        ]);
        $this->assertEquals('/foo/12345/bar/value?foo=1&bar=2', $route->__toString());
    }

    public function testEmptyQueryParamsAreIgnored()
    {
        $routePattern = '/foo/bar';
        $route = new Route($routePattern, [], [
            'foo' => null,
            'bar' => 'hello',
            'baz' => ''
        ]);
        $this->assertEquals('/foo/bar?bar=hello', $route->__toString());

        $route = new Route($routePattern, [], [
            'foo' => null,
            'bar' => ''
        ]);
        $this->assertEquals($routePattern, $route->__toString());
    }
}
