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
    const INTERACTION_NOTIFICATION_ROUTE    = '/rest/interactions/notification';
    const INTERACTION_STATUS_ROUTE          = '/rest/interactions/status';

    /**
     * Performs an interaction notification between two users
     *
     * @param User|string $user A User model or userId
     * @param User|string $targetUser A User model or userId
     * @param string $interactionCode
     * @param Item|string|null $item An Item model or itemId
     * @param array $categories
     * @return void
     */
    public function notifyInteraction($user, $targetUser, $interactionCode, $item = null, array $categories = []);

    /**
     * Returns information about the status of an interactionaccording to its limits
     *
     * @param User|string $user A User model or userId
     * @param User|string $targetUser A User model or userId
     * @param string $interactionCode
     * @param Item|string $item An Item model or itemId
     * @return array
     */
    public function getInteractionStatus($user, $targetUser, $interactionCode, $item);
}
