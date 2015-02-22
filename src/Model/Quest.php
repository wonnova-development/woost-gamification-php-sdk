<?php
namespace Wonnova\SDK\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Quest
 * @author Wonnova
 * @link http://www.wonnova.com
 *
 * @JMS\AccessType("public_method")
 */
class Quest extends AbstractModel
{
    /**
     * @var int
     * @JMS\Type("integer")
     */
    private $id;
    /**
     * @var \DateTime
     * @JMS\Type("StringDateTime")
     */
    private $startDate;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $code;
    /**
     * @var bool
     * @JMS\Type("boolean")
     */
    private $generatesNotification;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $name;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $description;

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
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     * @return $this
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
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
     * @return mixed
     */
    public function getGeneratesNotification()
    {
        return $this->generatesNotification;
    }

    /**
     * @param mixed $generatesNotification
     * @return $this
     */
    public function setGeneratesNotification($generatesNotification)
    {
        $this->generatesNotification = $generatesNotification;
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

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}
