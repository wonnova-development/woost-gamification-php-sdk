<?php
namespace Wonnova\SDK\Test\Model;

use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Model\Item;

/**
 * Class ItemTest
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class ItemTest extends TestCase
{
    /**
     * @var Item
     */
    private $item;

    public function setUp()
    {
        $this->item = new Item();
    }

    public function testItemId()
    {
        $expected = 'item123';
        $this->assertNull($this->item->getItemId());
        $this->assertSame($this->item, $this->item->setItemId($expected));
        $this->assertEquals($expected, $this->item->getItemId());
    }

    public function testTitle()
    {
        $expected = 'The Item';
        $this->assertNull($this->item->getTitle());
        $this->assertSame($this->item, $this->item->setTitle($expected));
        $this->assertEquals($expected, $this->item->getTitle());
    }

    public function testDescription()
    {
        $expected = 'The Item\'s description';
        $this->assertNull($this->item->getDescription());
        $this->assertSame($this->item, $this->item->setDescription($expected));
        $this->assertEquals($expected, $this->item->getDescription());
    }

    public function testAuthor()
    {
        $expected = 'john.doe';
        $this->assertNull($this->item->getAuthor());
        $this->assertSame($this->item, $this->item->setAuthor($expected));
        $this->assertEquals($expected, $this->item->getAuthor());
    }

    public function testScore()
    {
        $expected = 25;
        $this->assertNull($this->item->getScore());
        $this->assertSame($this->item, $this->item->setScore($expected));
        $this->assertEquals($expected, $this->item->getScore());
    }

    public function testDateCreated()
    {
        $expected = new \DateTime();
        $this->assertNull($this->item->getDateCreated());
        $this->assertSame($this->item, $this->item->setDateCreated($expected));
        $this->assertSame($expected, $this->item->getDateCreated());

        $this->item->setDateCreated('2010-01-01 00:00:00');
        $this->assertInstanceOf('DateTime', $this->item->getDateCreated());
    }

    public function testToArray()
    {
        $data = [
            'id' => 'item123',
            'title' => 'The Item',
            'description' => 'The Item\'s description',
            'author' => 'john.doe',
            'dateCreated' => '2010-01-01 00:00:00'
        ];
        $this->item->fromArray($data);
        $itemArray = $this->item->toArray();

        $this->assertCount(4, $itemArray);
        unset($data['dateCreated']);
        $this->assertEquals($data, $itemArray);
    }
}
