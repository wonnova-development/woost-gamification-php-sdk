<?php
namespace Wonnova\SDK\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Quest
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class Quest extends AbstractModel
{
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
     * @var int
     * @JMS\Type("integer")
     * @JMS\Accessor(getter="getProgress",setter="setProgress")
     */
    private $progress;
    /**
     * @var Collection|QuestStep[]
     * @JMS\Type("array<Wonnova\SDK\Model\QuestStep>")
     */
    private $questSteps;

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

    /**
     * @return int
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * @param int $progress
     * @return $this
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;
        return $this;
    }

    /**
     * @return Collection|QuestStep[]
     */
    public function getQuestSteps()
    {
        return $this->questSteps;
    }

    /**
     * @param Collection|QuestStep[] $questSteps
     * @return $this
     */
    public function setQuestSteps($questSteps)
    {
        $this->questSteps = $questSteps;
        return $this;
    }

    /**
     * This method is called via reflection, so it doesn't need to (and shouldn't) be exposed
     * It prevents the properties progress and questSteps to be null
     *
     * @JMS\PostDeserialize
     */
    private function postDeserialize()
    {
        if (is_null($this->progress)) {
            $this->progress = 0;
        }
        $this->questSteps = new ArrayCollection(is_null($this->questSteps) ? [] : $this->questSteps);
    }
}
