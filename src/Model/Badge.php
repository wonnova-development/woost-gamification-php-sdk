<?php
namespace Wonnova\SDK\Model;

use JMS\Serializer\Annotation as JMS;
use Wonnova\SDK\Common\WonnovaDateTimeParserTrait;

/**
 * Class Badge
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class Badge extends AbstractModel
{
    const TYPE_FIRST_OCCURRENCE = 'first_occurrence';
    const TYPE_REPETITION       = 'repetition';
    const TYPE_CONSECUTIVE_DAYS = 'consecutive_days';
    const TYPE_COMBO            = 'combo';

    use WonnovaDateTimeParserTrait;

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
     * @var string
     * @JMS\Type("string")
     */
    private $imageUrl;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $type;
    /**
     * @var \DateTime
     * @JMS\Type("WonnovaDateTime")
     */
    private $notificationDate;

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
     * @return \DateTime
     */
    public function getNotificationDate()
    {
        return $this->notificationDate;
    }

    /**
     * @param \DateTime|string $notificationDate
     * @return $this
     */
    public function setNotificationDate($notificationDate)
    {
        $this->notificationDate = $this->parseWonnovaDateTime($notificationDate);
        return $this;
    }
}
