<?php
namespace Wonnova\SDK\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Scenario
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class Scenario extends AbstractModel
{
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
