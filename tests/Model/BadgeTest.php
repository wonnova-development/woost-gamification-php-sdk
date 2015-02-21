<?php
namespace Wonnova\SDK\Test\Model;

use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Model\Badge;

class BadgeTest extends TestCase
{
    /**
     * @var Badge
     */
    private $badge;

    public function setUp()
    {
        $this->badge = new Badge();
    }

    public function testId()
    {
        $expected = 34;
        $this->assertNull($this->badge->getId());
        $this->assertSame($this->badge, $this->badge->setId($expected));
        $this->assertEquals($expected, $this->badge->getId());
    }

    public function testName()
    {
        $expected = 'The Badge';
        $this->assertNull($this->badge->getName());
        $this->assertSame($this->badge, $this->badge->setName($expected));
        $this->assertEquals($expected, $this->badge->getName());
    }

    public function testDescription()
    {
        $expected = 'This is a nice badge';
        $this->assertNull($this->badge->getDescription());
        $this->assertSame($this->badge, $this->badge->setDescription($expected));
        $this->assertEquals($expected, $this->badge->getDescription());
    }

    public function testImageUrl()
    {
        $expected = 'https://secure.wonnova.com/rest/badges/34/image';
        $this->assertNull($this->badge->getImageUrl());
        $this->assertSame($this->badge, $this->badge->setImageUrl($expected));
        $this->assertEquals($expected, $this->badge->getImageUrl());
    }

    public function testType()
    {
        $expected = Badge::TYPE_COMBO;
        $this->assertNull($this->badge->getType());
        $this->assertSame($this->badge, $this->badge->setType($expected));
        $this->assertEquals($expected, $this->badge->getType());
    }

    public function testNotificationDate()
    {
        $expected = new \DateTime('2010-01-05 10:00:00');
        $this->assertNull($this->badge->getNotificationDate());
        $this->assertSame($this->badge, $this->badge->setNotificationDate($expected));
        $this->assertSame($expected, $this->badge->getNotificationDate());
    }
}
