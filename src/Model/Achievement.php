<?php
namespace Wonnova\SDK\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Achievement
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class Achievement extends AbstractModel
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
     * @var int
     * @JMS\Type("integer")
     */
    private $value;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Returns the list of valid types
     *
     * @return array
     */
    public static function getAllTypesList()
    {
        return [self::TYPE_BADGE, self::TYPE_LEVEL, self::TYPE_POINTS, self::TYPE_QUEST];
    }
}
