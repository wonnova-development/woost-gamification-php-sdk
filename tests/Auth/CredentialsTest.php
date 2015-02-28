<?php
namespace Wonnova\SDK\Test\Auth;

use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Auth\Credentials;

/**
 * Class CredentialsTest
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class CredentialsTest extends TestCase
{
    public function testCreateFromArray()
    {
        $expected = 'expected_value';
        $data = ['key' => $expected];
        $credentials = new Credentials($data);
        $this->assertEquals($expected, $credentials->getKey());
    }

    public function testCreateFromString()
    {
        $expected = 'expected_value';
        $credentials = new Credentials($expected);
        $this->assertEquals($expected, $credentials->getKey());
    }

    /**
     * @expectedException \Wonnova\SDK\Exception\InvalidArgumentException
     */
    public function testCreateFromInvalidArray()
    {
        new Credentials([]);
    }

    /**
     * @expectedException \Wonnova\SDK\Exception\InvalidArgumentException
     */
    public function testCreateFromInvalidValue()
    {
        new Credentials(123);
    }

    public function testToArray()
    {
        $data = ['key' => 'expected_value'];
        $credentials = new Credentials($data);
        $this->assertEquals($data, $credentials->toArray());
    }
}
