<?php
namespace Wonnova\SDK\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Notification
 * @author Wonnova
 * @link http://www.wonnova.com
 *
 * @JMS\AccessType("public_method")
 */
class Notification extends AbstractModel
{
    const TYPE_POINTS = 'points';
    const TYPE_LEVEL = 'level';
    const TYPE_BADGE = 'badges'; // In singular for consistency
    const TYPE_QUEST = 'quest';

    /**
     * Just an allias for TYPE_BADGE. This will be removed at some point if the public API is updated
     * @deprecated Use TYPE_BADGE constant instead.
     */
    const TYPE_BADGES = 'badges';


    /**
     * @var string
     * @JMS\Type("string")
     */
    private $type;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $message;

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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
}
