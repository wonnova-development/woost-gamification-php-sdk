<?php
namespace Wonnova\SDK\Test\Model;

use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Model\Achievement;
use Wonnova\SDK\Model\Notification;

class NotificationTest extends TestCase
{
    /**
     * @var Notification
     */
    private $notification;

    public function setUp()
    {
        $this->notification = new Notification();
    }

    public function testType()
    {
        $expected = Achievement::TYPE_BADGE;
        $this->assertNull($this->notification->getType());
        $this->assertSame($this->notification, $this->notification->setType($expected));
        $this->assertEquals($expected, $this->notification->getType());
    }

    public function testMessage()
    {
        $expected = 'Congratulations!! You have won a badge!!';
        $this->assertNull($this->notification->getMessage());
        $this->assertSame($this->notification, $this->notification->setMessage($expected));
        $this->assertEquals($expected, $this->notification->getMessage());
    }
}
