<?php
namespace Wonnova\SDK\Test\Model;

use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Model\QuestStep;

class QuestStepTest extends TestCase
{
    /**
     * @var QuestStep
     */
    private $questStep;

    public function setUp()
    {
        $this->questStep = new QuestStep();
    }

    public function testId()
    {
        $expected = 85;
        $this->assertNull($this->questStep->getId());
        $this->assertSame($this->questStep, $this->questStep->setId($expected));
        $this->assertEquals($expected, $this->questStep->getId());
    }

    public function testType()
    {
        $expected = QuestStep::TYPE_BADGE;
        $this->assertNull($this->questStep->getType());
        $this->assertSame($this->questStep, $this->questStep->setType($expected));
        $this->assertEquals($expected, $this->questStep->getType());
    }

    public function testCode()
    {
        $expected = 'THE_CODE';
        $this->assertNull($this->questStep->getCode());
        $this->assertSame($this->questStep, $this->questStep->setCode($expected));
        $this->assertEquals($expected, $this->questStep->getCode());
    }

    public function testName()
    {
        $expected = 'The name';
        $this->assertNull($this->questStep->getName());
        $this->assertSame($this->questStep, $this->questStep->setName($expected));
        $this->assertEquals($expected, $this->questStep->getName());
    }

    public function testDescription()
    {
        $expected = 'A very long description';
        $this->assertNull($this->questStep->getDescription());
        $this->assertSame($this->questStep, $this->questStep->setDescription($expected));
        $this->assertEquals($expected, $this->questStep->getDescription());
    }

    public function testCompleted()
    {
        $expected = false;
        $this->assertNull($this->questStep->isCompleted());
        $this->assertSame($this->questStep, $this->questStep->setCompleted($expected));
        $this->assertEquals($expected, $this->questStep->isCompleted());
    }
}
