<?php
namespace Wonnova\SDK\Test\Model;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Model\Quest;
use Wonnova\SDK\Model\QuestStep;

/**
 * Class QuestTest
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class QuestTest extends TestCase
{
    /**
     * @var Quest
     */
    private $quest;

    public function setUp()
    {
        $this->quest = new Quest();
    }

    public function testStartDate()
    {
        $expected = new \DateTime();
        $this->assertNull($this->quest->getStartDate());
        $this->assertSame($this->quest, $this->quest->setStartDate($expected));
        $this->assertSame($expected, $this->quest->getStartDate());

        $this->quest->setStartDate('2010-01-01 00:00:00');
        $this->assertInstanceOf('DateTime', $this->quest->getStartDate());
    }

    public function testCode()
    {
        $expected = 'THE_QUEST';
        $this->assertNull($this->quest->getCode());
        $this->assertSame($this->quest, $this->quest->setCode($expected));
        $this->assertSame($expected, $this->quest->getCode());
    }

    public function testGeneratesNotification()
    {
        $expected = true;
        $this->assertNull($this->quest->getGeneratesNotification());
        $this->assertSame($this->quest, $this->quest->setGeneratesNotification($expected));
        $this->assertEquals($expected, $this->quest->getGeneratesNotification());
    }

    public function testName()
    {
        $expected = 'The Quest';
        $this->assertNull($this->quest->getName());
        $this->assertSame($this->quest, $this->quest->setName($expected));
        $this->assertSame($expected, $this->quest->getName());
    }

    public function testDescription()
    {
        $expected = 'The Quest\'s description';
        $this->assertNull($this->quest->getDescription());
        $this->assertSame($this->quest, $this->quest->setDescription($expected));
        $this->assertSame($expected, $this->quest->getDescription());
    }

    public function testProgress()
    {
        $expected = 100;
        $this->assertNull($this->quest->getProgress());
        $this->assertSame($this->quest, $this->quest->setProgress($expected));
        $this->assertSame($expected, $this->quest->getProgress());
    }

    public function testQuestSteps()
    {
        $stepsArray = [new QuestStep(), new QuestStep()];
        $expected = new ArrayCollection([new QuestStep(), new QuestStep()]);
        $this->assertNull($this->quest->getQuestSteps());
        $this->assertSame($this->quest, $this->quest->setQuestSteps($expected));
        $this->assertSame($expected, $this->quest->getQuestSteps());

        $this->quest->setQuestSteps($stepsArray);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->quest->getQuestSteps());
    }
}
