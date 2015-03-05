<?php
namespace Wonnova\SDK\Test\Model;

use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Model\User;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    public function setUp()
    {
        $this->user = new User();
    }

    public function testUserId()
    {
        $expected = '273c4392-4c5k-4c3b-a70e-72375216702f';
        $this->assertNull($this->user->getUserId());
        $this->assertSame($this->user, $this->user->setUserId($expected));
        $this->assertEquals($expected, $this->user->getUserId());
    }

    public function testUsername()
    {
        $expected = 'foobar';
        $this->assertNull($this->user->getUsername());
        $this->assertSame($this->user, $this->user->setUsername($expected));
        $this->assertEquals($expected, $this->user->getUsername());
    }

    public function testProvider()
    {
        $expected = 'facebook';
        $this->assertNull($this->user->getProvider());
        $this->assertSame($this->user, $this->user->setProvider($expected));
        $this->assertEquals($expected, $this->user->getProvider());
    }

    public function testFullName()
    {
        $expected = 'Jane Doe';
        $this->assertNull($this->user->getFullName());
        $this->assertSame($this->user, $this->user->setFullName($expected));
        $this->assertEquals($expected, $this->user->getFullName());
    }

    public function testAvatar()
    {
        $expected = 'https://graph.facebook.com';
        $this->assertNull($this->user->getAvatar());
        $this->assertSame($this->user, $this->user->setAvatar($expected));
        $this->assertEquals($expected, $this->user->getAvatar());
    }

    public function testDateOfBirth()
    {
        $expected = new \DateTime('1990-05-08 00:00:00');
        $this->assertNull($this->user->getDateOfBirth());
        $this->assertSame($this->user, $this->user->setDateOfBirth($expected));
        $this->assertSame($expected, $this->user->getDateOfBirth());

        $this->user->setDateOfBirth(['date' => '1990-05-08 00:00:00']);
        $this->assertInstanceOf('DateTime', $this->user->getDateOfBirth());

        $this->user->setDateOfBirth(['date' => '1990-05-08 00:00:00', 'timezone' => 'Europe/Berlin']);
        $this->assertInstanceOf('DateTime', $this->user->getDateOfBirth());

        $this->user->setDateOfBirth('1990-05-08 00:00:00');
        $this->assertInstanceOf('DateTime', $this->user->getDateOfBirth());
    }

    public function testInvalidDateOfBirthSetsItToNull()
    {
        $this->user->setDateOfBirth(new \stdClass());
        $this->assertNull($this->user->getDateOfBirth());
    }

    public function testEmail()
    {
        $expected = 'foo@bar.com';
        $this->assertNull($this->user->getEmail());
        $this->assertSame($this->user, $this->user->setEmail($expected));
        $this->assertEquals($expected, $this->user->getEmail());
    }

    public function testAddress()
    {
        $expected = 'Fake address';
        $this->assertNull($this->user->getAddress());
        $this->assertSame($this->user, $this->user->setAddress($expected));
        $this->assertEquals($expected, $this->user->getAddress());
    }

    public function testCity()
    {
        $expected = 'New York';
        $this->assertNull($this->user->getCity());
        $this->assertSame($this->user, $this->user->setCity($expected));
        $this->assertEquals($expected, $this->user->getCity());
    }

    public function testCountry()
    {
        $expected = 'Italy';
        $this->assertNull($this->user->getCountry());
        $this->assertSame($this->user, $this->user->setCountry($expected));
        $this->assertEquals($expected, $this->user->getCountry());
    }

    public function testPostalCode()
    {
        $expected = '12345';
        $this->assertNull($this->user->getPostalCode());
        $this->assertSame($this->user, $this->user->setPostalCode($expected));
        $this->assertEquals($expected, $this->user->getPostalCode());
    }

    public function testPhone()
    {
        $expected = '555-1234';
        $this->assertNull($this->user->getPhone());
        $this->assertSame($this->user, $this->user->setPhone($expected));
        $this->assertEquals($expected, $this->user->getPhone());
    }

    public function testGender()
    {
        $expected = 'male';
        $this->assertNull($this->user->getGender());
        $this->assertSame($this->user, $this->user->setGender($expected));
        $this->assertEquals($expected, $this->user->getGender());
    }

    public function testLocale()
    {
        $expected = 'en_US';
        $this->assertNull($this->user->getLocale());
        $this->assertSame($this->user, $this->user->setLocale($expected));
        $this->assertEquals($expected, $this->user->getLocale());
    }

    public function testTimezone()
    {
        $expected = 'Europe/London';
        $this->assertNull($this->user->getTimezone());
        $this->assertSame($this->user, $this->user->setTimezone($expected));
        $this->assertEquals($expected, $this->user->getTimezone());
    }
}
