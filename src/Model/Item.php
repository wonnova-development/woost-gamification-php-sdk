<?php
namespace Wonnova\SDK\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Item
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class Item extends AbstractModel
{
    /**
     * Used to map virtual to real fields
     *
     * @var array
     * @JMS\Exclude()
     */
    protected $fieldMapping = [
        'id' => 'itemId'
    ];

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("id")
     */
    private $itemId;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $title;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $description;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $author;
    /**
     * @var integer
     * @JMS\Type("integer")
     */
    private $score;
    /**
     * @var \DateTime
     * @JMS\Type("StringDateTime")
     */
    private $dateCreated;

    /**
     * @return mixed
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param mixed $itemId
     * @return $this
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;
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
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     * @return $this
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = is_string($dateCreated) ? new \DateTime($dateCreated) : $dateCreated;
        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->itemId,
            'title' => $this->title,
            'description' => $this->description,
            'author' => $this->author
        ];
    }
}
