<?php
namespace Wonnova\SDK\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Action
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class Action extends AbstractModel
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $actionCode;
    /**
     * @var Item
     * @JMS\Type("\Wonnova\SDK\Model\Item")
     */
    private $item;
    /**
     * @var array
     * @JMS\Type("array<string>")
     */
    private $categories;

    /**
     * Get actionCode
     * @return string
     */
    public function getActionCode()
    {
        return $this->actionCode;
    }

    /**
     * Set actionCode
     * @param string $actionCode
     * @return $this
     */
    public function setActionCode($actionCode)
    {
        $this->actionCode = $actionCode;
        return $this;
    }

    /**
     * Get item
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set item
     * @param Item $item
     * @return $this
     */
    public function setItem($item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * Get categories
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set categories
     * @param array $categories
     * @return $this
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $actionArray = [];

        $actionArray['actionCode'] = $this->actionCode;

        if (isset($this->item)) {
            $actionArray['item'] = $this->item->toArray();
        }
        if (! empty($this->categories)) {
            $actionArray['categories'] = $this->categories;
        }

        return $actionArray;
    }
}
