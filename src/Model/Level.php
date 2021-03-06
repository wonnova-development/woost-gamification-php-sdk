<?php
namespace Wonnova\SDK\Model;

use JMS\Serializer\Annotation as JMS;
use Wonnova\SDK\Common\WonnovaDateTimeParserTrait;

/**
 * Class Level
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class Level extends AbstractModel
{
    use WonnovaDateTimeParserTrait;

    /**
     * Used to map virtual to real fields
     *
     * @var array
     */
    protected $fieldMapping = [
        'dynamic' => 'scenario'
    ];

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
     * @var int
     * @JMS\Type("integer")
     */
    private $score;
    /**
     * @var boolean
     * @JMS\Type("boolean")
     */
    private $generatesNotification;
    /**
     * @var boolean
     * @JMS\Type("boolean")
     */
    private $categoryEnabled;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $imageUrl;
    /**
     * @var \DateTime
     * @JMS\Type("WonnovaDateTime")
     */
    private $dateCreated;
    /**
     * @var Badge
     * @JMS\Type("Wonnova\SDK\Model\Badge")
     */
    private $badge;
    /**
     * @var Scenario
     * @JMS\Type("Wonnova\SDK\Model\Scenario")
     * @JMS\SerializedName("dynamic")
     */
    private $scenario;

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
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param int $score
     * @return $this
     */
    public function setScore($score)
    {
        $this->score = $score;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getGeneratesNotification()
    {
        return $this->generatesNotification;
    }

    /**
     * @param boolean $generatesNotification
     * @return $this
     */
    public function setGeneratesNotification($generatesNotification)
    {
        $this->generatesNotification = $generatesNotification;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isCategoryEnabled()
    {
        return $this->categoryEnabled;
    }

    /**
     * @param boolean $categoryEnabled
     * @return $this
     */
    public function setCategoryEnabled($categoryEnabled)
    {
        $this->categoryEnabled = $categoryEnabled;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     * @return $this
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime|string $dateCreated
     * @return $this
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $this->parseWonnovaDateTime($dateCreated);
        return $this;
    }

    /**
     * @return Badge
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * @param mixed $badge
     * @return $this
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;
        return $this;
    }

    /**
     * @return Scenario
     */
    public function getScenario()
    {
        return $this->scenario;
    }

    /**
     * @param mixed $scenario
     * @return $this
     */
    public function setScenario($scenario)
    {
        $this->scenario = $scenario;
        return $this;
    }
}
