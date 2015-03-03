<?php
namespace Wonnova\SDK\Test\Model;

use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Model\Badge;
use Wonnova\SDK\Model\Level;
use Wonnova\SDK\Model\Scenario;

/**
 * Class LevelTest
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class LevelTest extends TestCase
{
    /**
     * @var Level
     */
    private $level;

    public function setUp()
    {
        $this->level = new Level();
    }

    public function testCode()
    {
        $expected = 'THE_LEVEL';
        $this->assertNull($this->level->getCode());
        $this->assertSame($this->level, $this->level->setCode($expected));
        $this->assertEquals($expected, $this->level->getCode());
    }

    public function testName()
    {
        $expected = 'The Level';
        $this->assertNull($this->level->getName());
        $this->assertSame($this->level, $this->level->setName($expected));
        $this->assertEquals($expected, $this->level->getName());
    }

    public function testScore()
    {
        $expected = 500;
        $this->assertNull($this->level->getScore());
        $this->assertSame($this->level, $this->level->setScore($expected));
        $this->assertEquals($expected, $this->level->getScore());
    }

    public function testGeneratesNotification()
    {
        $expected = true;
        $this->assertNull($this->level->getGeneratesNotification());
        $this->assertSame($this->level, $this->level->setGeneratesNotification($expected));
        $this->assertEquals($expected, $this->level->getGeneratesNotification());
    }

    public function testCategoryEnabled()
    {
        $expected = false;
        $this->assertNull($this->level->isCategoryEnabled());
        $this->assertSame($this->level, $this->level->setCategoryEnabled($expected));
        $this->assertEquals($expected, $this->level->isCategoryEnabled());
    }

    public function testImageUrl()
    {
        $expected = 'https://secure.wonnova.com/image';
        $this->assertNull($this->level->getImageUrl());
        $this->assertSame($this->level, $this->level->setImageUrl($expected));
        $this->assertEquals($expected, $this->level->getImageUrl());
    }

    public function testDateCreated()
    {
        $expected = new \DateTime();
        $this->assertNull($this->level->getDateCreated());
        $this->assertSame($this->level, $this->level->setDateCreated($expected));
        $this->assertSame($expected, $this->level->getDateCreated());

        $this->level->setDateCreated('2010-01-01 00:00:00');
        $this->assertInstanceOf('DateTime', $this->level->getDateCreated());
    }

    public function testBadge()
    {
        $expected = new Badge();
        $this->assertNull($this->level->getBadge());
        $this->assertSame($this->level, $this->level->setBadge($expected));
        $this->assertEquals($expected, $this->level->getBadge());
    }

    public function testScenario()
    {
        $expected = new Scenario();
        $this->assertNull($this->level->getScenario());
        $this->assertSame($this->level, $this->level->setScenario($expected));
        $this->assertEquals($expected, $this->level->getScenario());
    }
}
