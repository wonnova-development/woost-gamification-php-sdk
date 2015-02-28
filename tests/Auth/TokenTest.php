<?php
namespace Wonnova\SDK\Test\Auth;

use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Auth\Token;
use Wonnova\SDK\Common\Headers;

/**
 * Class TokenTest
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class TokenTest extends TestCase
{
    /**
     * @var Token
     */
    private $token;

    public function setUp()
    {
        $this->token = new Token();
    }

    public function testAccesToken()
    {
        $this->assertNull($this->token->getAccessToken());
        $this->token->setAccessToken('foo');
        $this->assertEquals('foo', $this->token->getAccessToken());
    }

    public function testToArray()
    {
        $this->token->setAccessToken('foo');
        $this->assertEquals([Headers::TOKEN_HEADER => 'foo'], $this->token->toArray());
    }
}
