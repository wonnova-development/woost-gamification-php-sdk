<?php
namespace Wonnova\SDK\Connection\API;

use Wonnova\SDK\Model\Item;
use Wonnova\SDK\Model\User;

/**
 * Interface InteractionsAPIInterface
 * @author Wonnova
 * @link http://www.wonnova.com
 */
interface InteractionsAPIInterface
{
    const INTERACTION_NOTIFICATION_ROUTE = '/interactions/notification';

    /**
     * Performs an interaction notification between two users
     *
     * @param User|string $user A User model or userId
     * @param User|string $targetUser A User model or userId
     * @param string $interactionCode
     * @param Item|null $item An Item model or itemId
     * @param array $categories
     * @return void
     */
    public function notifyInteraction($user, $targetUser, $interactionCode, $item = null, array $categories = []);
}
