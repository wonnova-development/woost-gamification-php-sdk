<?php
namespace Wonnova\SDK\Test\Common;

use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Common\URIUtils;

class URIUtilsTest extends TestCase
{
    public function testParseUri()
    {
        $basePath = '/foo/%bar%/baz/%userId%';
        $this->assertEquals('/rest/foo/replaced/baz/123', URIUtils::parseUri($basePath, [
            'bar' => 'replaced',
            'userId' => 123
        ]));

        $basePath = '/hello/%foo%/goodbye';
        $this->assertEquals('/rest/hello/replaced/goodbye', URIUtils::parseUri($basePath, [
            'foo' => 'replaced',
            'ignoredArgument' => 'bar',
        ]));
    }
}
