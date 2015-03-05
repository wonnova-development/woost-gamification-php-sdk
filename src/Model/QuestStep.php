<?php
namespace Wonnova\SDK\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class QuestStep
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class QuestStep extends AbstractModel
{
    const TYPE_BADGE = 'badge';
    const TYPE_QUEST = 'quest';
    const TYPE_ACTION = 'action';

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $type;
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
     * @var string
     * @JMS\Type("string")
     */
    private $description;
    /**
     * @var boolean
     * @JMS\Type("boolean")
     */
    private $completed;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
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

    /**
     * @return boolean
     */
    public function isCompleted()
    {
        return $this->completed;
    }

    /**
     * @param mixed $completed
     * @return $this
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
        return $this;
    }
}
