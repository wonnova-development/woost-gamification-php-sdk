<?php
namespace Wonnova\SDK\Common;

/**
 * Interface ArraySerializableInterface
 * @author Wonnova
 * @link http://www.wonnova.com
 */
interface ArraySerializableInterface
{
    /**
     * Populates this object from an array of data
     *
     * @param array $data
     */
    public function fromArray(array $data);

    /**
     * Returns a copy data array of this object
     *
     * @return array
     */
    public function toArray();
}
