<?php
namespace Wonnova\SDK\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Scenario
 * @author Wonnova
 * @link http://www.wonnova.com
 *
 * @JMS\AccessType("public_method")
 */
class Scenario extends AbstractModel
{
    /**
     * @var int
     * @JMS\Type("integer")
     */
    private $id;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $code;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
