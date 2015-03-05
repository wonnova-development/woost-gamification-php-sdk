<?php
namespace Wonnova\SDK\Test\Model;

use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Model\Team;

/**
 * Class TeamTest
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class TeamTest extends TestCase
{
    /**
     * @var Team
     */
    private $team;

    public function setUp()
    {
        $this->team = new Team();
    }

    public function testTeamName()
    {
        $expected = 'The A Team';
        $this->assertNull($this->team->getTeamName());
        $this->assertSame($this->team, $this->team->setTeamName($expected));
        $this->assertEquals($expected, $this->team->getTeamName());
    }

    public function testAvatar()
    {
        $expected = 'https://secure.wonnova.com/avatar';
        $this->assertNull($this->team->getAvatar());
        $this->assertSame($this->team, $this->team->setAvatar($expected));
        $this->assertEquals($expected, $this->team->getAvatar());
    }

    public function testDescription()
    {
        $expected = 'Team\'s description';
        $this->assertNull($this->team->getDescription());
        $this->assertSame($this->team, $this->team->setDescription($expected));
        $this->assertEquals($expected, $this->team->getDescription());
    }

    public function testScore()
    {
        $expected = 300;
        $this->assertNull($this->team->getScore());
        $this->assertSame($this->team, $this->team->setScore($expected));
        $this->assertEquals($expected, $this->team->getScore());
    }

    public function testItsMe()
    {
        $expected = true;
        $this->assertNull($this->team->getItsMe());
        $this->assertSame($this->team, $this->team->setItsMe($expected));
        $this->assertEquals($expected, $this->team->getItsMe());
    }

    public function testPosition()
    {
        $expected = 3;
        $this->assertNull($this->team->getPosition());
        $this->assertSame($this->team, $this->team->setPosition($expected));
        $this->assertEquals($expected, $this->team->getPosition());
    }
}
