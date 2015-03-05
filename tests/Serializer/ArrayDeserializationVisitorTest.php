<?php
namespace Wonnova\SDK\Test\Serializer;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use PHPUnit_Framework_TestCase as TestCase;
use Wonnova\SDK\Serializer\ArrayDeserializationVisitor;

/**
 * Class ArrayDeserializationVisitorTest
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class ArrayDeserializationVisitorTest extends TestCase
{
    /**
     * @var ArrayDeserializationVisitor
     */
    private $visitor;

    public function setUp()
    {
        $this->visitor = new ArrayDeserializationVisitor(new SerializedNameAnnotationStrategy(
            new IdenticalPropertyNamingStrategy()
        ));
    }

    public function testDecodeIterableObject()
    {
        $expected = [1, 2, 3];
        $data = new ArrayCollection($expected);
        $this->assertEquals($expected, $this->visitor->prepare($data));
        $this->assertCount(0, $this->visitor->prepare(new ArrayCollection()));
    }

    public function testDecodeArray()
    {
        $expected = [1, 2, 3];
        $this->assertEquals($expected, $this->visitor->prepare($expected));
    }

    public function testDecodeEmptyValue()
    {
        $expected = [];
        $this->assertEquals($expected, $this->visitor->prepare(null));
        $this->assertEquals($expected, $this->visitor->prepare([]));
        $this->assertEquals($expected, $this->visitor->prepare(''));
    }

    /**
     * @expectedException \JMS\Serializer\Exception\RuntimeException
     */
    public function testInvalidArgumentThrowsException()
    {
        $this->visitor->prepare(new \stdClass());
    }
}
