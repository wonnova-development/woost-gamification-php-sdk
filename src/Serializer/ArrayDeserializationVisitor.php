<?php
namespace Wonnova\SDK\Serializer;

use JMS\Serializer\GenericDeserializationVisitor;
use JMS\Serializer\Exception\RuntimeException;

/**
 * This deserialization visitor just bypasses arrays, so that objects can be hydrated with data arrays
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class ArrayDeserializationVisitor extends GenericDeserializationVisitor
{
    protected function decode($str)
    {
        if ($str instanceof \Traversable) {
            $str = iterator_to_array($str);
        }

        if (! is_array($str)) {
            throw new RuntimeException(
                'Provided value is not an array or Traversable instance and could not be deserialized'
            );
        }

        return $str;
    }
}
