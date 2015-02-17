<?php
namespace Wonnova\SDK\Model;

use Wonnova\SDK\Common\ArraySerializableInterface;

/**
 * Class AbstractModel
 * @author Wonnova
 * @link http://www.wonnova.com
 */
abstract class AbstractModel implements ArraySerializableInterface, \JsonSerializable
{
    /**
     * Returns a copy data array of this object
     *
     * @return array
     */
    public function toArray()
    {
        return $this->jsonSerialize();
    }
}
