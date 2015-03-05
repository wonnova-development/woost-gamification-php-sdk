<?php
namespace Wonnova\SDK\Connection\API;

use Doctrine\Common\Collections\Collection;
use Wonnova\SDK\Model\Item;
use Wonnova\SDK\Model\User;

/**
 * Interface ItemsAPIInterface
 * @author Wonnova
 * @link http://www.wonnova.com
 */
interface ItemsAPIInterface
{
    const ITEMS_LEADERBOARD_ROUTE   = '/rest/items/leaderboard';
    const ITEM_RATE_ROUTE           = '/rest/items/rate';
    const ITEM_ROUTE                = '/rest/items/%itemId%';
    const ITEM_RESET_SCORE_ROUTE    = '/rest/items/%itemId%/reset-score';

    /**
     * Returns the top rated items
     *
     * @param int $maxCount
     * @return Collection|Item[]
     */
    public function getItemsLeaderboard($maxCount = 6);

    /**
     * Rates an item increasing its score and setting the rate from certain user.
     *
     * @param User|string $user a User model or userId
     * @param Item|string $item an Item model or itemId
     * @param int $score
     * @return Item
     */
    public function rateItem($user, $item, $score = 0);

    /**
     * Deletes certain item
     *
     * @param Item|string $item an Item model or itemId
     * @return void
     */
    public function deleteItem($item);

    /**
     * Resets an item's score to zero
     *
     * @param Item|string $item an Item model or itemId
     * @return void
     */
    public function resetItemScore($item);
}
