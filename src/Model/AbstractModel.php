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
     * Used to map virtual to real fields
     *
     * @var array
     */
    protected $fieldMapping = [];

    /**
     * Populates this object from an array of data
     *
     * @param array $data
     */
    public function fromArray(array $data)
    {
        foreach ($data as $property => $value) {
            $setter = 'set' . ucfirst($property);
            if (method_exists($this, $setter)) {
                $this->{$setter}($value);
            } elseif (isset($this->fieldMapping[$property])) {
                // Try an alternative property from the mapping definition
                $property = $this->fieldMapping[$property];
                $setter = 'set' . ucfirst($property);
                if (method_exists($this, $setter)) {
                    $this->{$setter}($value);
                }
            }
        }
    }

    /**
     * Returns a copy data array of this object
     *
     * @return array
     */
    public function toArray()
    {
        return [];
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
