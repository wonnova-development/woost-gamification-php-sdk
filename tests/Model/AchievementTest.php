<?php
namespace Wonnova\SDK\Test\Model;

use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Model\Achievement;

class AchievementTest extends TestCase
{
    /**
     * @var Achievement
     */
    private $achievement;

    public function setUp()
    {
        $this->achievement = new Achievement();
    }

    public function testType()
    {
        $expected = Achievement::TYPE_POINTS;
        $this->assertNull($this->achievement->getType());
        $this->assertSame($this->achievement, $this->achievement->setType($expected));
        $this->assertEquals($expected, $this->achievement->getType());
    }

    public function testValue()
    {
        $expected = 8;
        $this->assertNull($this->achievement->getValue());
        $this->assertSame($this->achievement, $this->achievement->setValue($expected));
        $this->assertEquals($expected, $this->achievement->getValue());
    }

    public function testGetTypesList()
    {
        $list = Achievement::getAllTypesList();
        $this->assertCount(4, $list);
        $this->assertTrue(in_array(Achievement::TYPE_BADGE, $list));
        $this->assertTrue(in_array(Achievement::TYPE_LEVEL, $list));
        $this->assertTrue(in_array(Achievement::TYPE_POINTS, $list));
        $this->assertTrue(in_array(Achievement::TYPE_QUEST, $list));
    }
}
