<?php
namespace Wonnova\SDK\Test\Model;

use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Model\Action;
use Wonnova\SDK\Model\Item;

/**
 * Class ActionTest
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class ActionTest extends TestCase
{
    /**
     * @var Action
     */
    private $action;

    public function setUp()
    {
        $this->action = new Action();
    }

    public function testActionCode()
    {
        $expected = 'LOGIN';
        $this->assertNull($this->action->getActionCode());
        $this->assertSame($this->action, $this->action->setActionCode($expected));
        $this->assertEquals($expected, $this->action->getActionCode());
    }

    public function testCategories()
    {
        $expected = ['foo', 'bar'];
        $this->assertNull($this->action->getCategories());
        $this->assertSame($this->action, $this->action->setCategories($expected));
        $this->assertEquals($expected, $this->action->getCategories());
    }

    public function testItem()
    {
        $expected = new Item();
        $expected->setItemId('1324')
            ->setAuthor('foo')
            ->setTitle('bar');
        $this->assertNull($this->action->getItem());
        $this->assertSame($this->action, $this->action->setItem($expected));
        $this->assertEquals($expected, $this->action->getItem());
    }

    public function testToArray()
    {
        $data = [
            'actionCode' => 'LOGIN',
            'item' => [
                'id' => 'The Item',
                'title' => 'The Item',
                'description' => 'The Item\'s description',
                'author' => 'john.doe',
            ],
            'categories' => ['foo', 'bar'],
        ];
        $item = new Item();
        $item->setItemId('The Item')
            ->setTitle('The Item')
            ->setDescription('The Item\'s description')
            ->setAuthor('john.doe');

        $this->action->fromArray($data);
        $this->action->setItem($item);
        $actionArray = $this->action->toArray();

        $this->assertCount(3, $actionArray);
        $this->assertEquals($data, $actionArray);
    }
}
