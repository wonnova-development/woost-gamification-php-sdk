<?php
namespace Wonnova\SDK\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Team
 * @author Wonnova
 * @link http://www.wonnova.com
 *
 * @JMS\AccessType("public_method")
 */
class Team extends AbstractModel
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $teamName;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $avatar;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $description;
    /**
     * @var int
     * @JMS\Type("integer")
     */
    private $score;
    /**
     * @var boolean
     * @JMS\Type("boolean")
     */
    private $itsMe;
    /**
     * @var int
     * @JMS\Type("integer")
     */
    private $position;

    /**
     * @return string
     */
    public function getTeamName()
    {
        return $this->teamName;
    }

    /**
     * @param string $teamName
     * @return $this
     */
    public function setTeamName($teamName)
    {
        $this->teamName = $teamName;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
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
    public function isItsMe()
    {
        return $this->itsMe;
    }

    /**
     * @param boolean $itsMe
     * @return $this
     */
    public function setItsMe($itsMe)
    {
        $this->itsMe = $itsMe;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }
}
