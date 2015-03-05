<?php
namespace Wonnova\SDK\Test\Model;

use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Model\Scenario;

/**
 * Class ScenarioTest
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class ScenarioTest extends TestCase
{
    /**
     * @var Scenario
     */
    private $scenario;

    public function setUp()
    {
        $this->scenario = new Scenario();
    }

    public function testCode()
    {
        $expected = 'SCENARIO';
        $this->assertNull($this->scenario->getCode());
        $this->assertSame($this->scenario, $this->scenario->setCode($expected));
        $this->assertEquals($expected, $this->scenario->getCode());
    }

    public function testName()
    {
        $expected = 'Scenario';
        $this->assertNull($this->scenario->getName());
        $this->assertSame($this->scenario, $this->scenario->setName($expected));
        $this->assertEquals($expected, $this->scenario->getName());
    }
}
