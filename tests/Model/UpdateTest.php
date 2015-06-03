<?php
namespace Wonnova\SDK\Test\Model;

use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Model\Update;

/**
 * Class UpdateTest
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class UpdateTest extends TestCase
{
    /**
     * @var Update
     */
    private $update;

    public function setUp()
    {
        $this->update = new Update();
    }

    public function testText()
    {
        $expected = 'You just won 10 points';
        $this->assertNull($this->update->getText());
        $this->assertSame($this->update, $this->update->setText($expected));
        $this->assertEquals($expected, $this->update->getText());
    }

    public function testDate()
    {
        $expected = new \DateTime('1990-05-08 00:00:00');
        $this->assertNull($this->update->getDate());
        $this->assertSame($this->update, $this->update->setDate($expected));
        $this->assertSame($expected, $this->update->getDate());

        $this->update->setDate(['date' => '1990-05-08 00:00:00']);
        $this->assertInstanceOf('DateTime', $this->update->getDate());

        $this->update->setDate(['date' => '1990-05-08 00:00:00', 'timezone' => 'Europe/Berlin']);
        $this->assertInstanceOf('DateTime', $this->update->getDate());

        $this->update->setDate('1990-05-08 00:00:00');
        $this->assertInstanceOf('DateTime', $this->update->getDate());
    }

    public function testType()
    {
        $expected = Update::TYPE_INTERACTION;
        $this->assertNull($this->update->getType());
        $this->assertSame($this->update, $this->update->setType($expected));
        $this->assertEquals($expected, $this->update->getType());
    }
}
