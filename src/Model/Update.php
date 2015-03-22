<?php
namespace Wonnova\SDK\Model;

use JMS\Serializer\Annotation as JMS;
use Wonnova\SDK\Common\WonnovaDateTimeParserTrait;

/**
 * Class Update
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class Update extends AbstractModel
{
    use WonnovaDateTimeParserTrait;

    const TYPE_ACTION       = 'action';
    const TYPE_INTERACTION  = 'interaction';
    const TYPE_BADGE        = 'badge';
    const TYPE_QUEST        = 'quest';
    const TYPE_LEVEL        = 'level';

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $text;
    /**
     * @var \DateTime
     * @JMS\Type("WonnovaDateTime")
     */
    private $date;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $type;

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime|array|string $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $this->parseWonnovaDateTime($date);
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
}
