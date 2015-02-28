<?php
namespace Wonnova\SDK\Connection\API;

use Wonnova\SDK\Model\Item;
use Wonnova\SDK\Model\User;

/**
 * Interface ActionsAPIInterface
 * @author Wonnova
 * @link http://www.wonnova.com
 */
interface ActionsAPIInterface
{
    const ACTION_NOTIFICATION_ROUTE = '/actions/notification';

    /**
     * Performs an action notification from certain user
     *
     * @param User|string $user A User model or userId
     * @param string $actionCode
     * @param Item|string $item An Item model or itemId
     * @param array $categories
     * @return void
     */
    public function notifyAction($user, $actionCode, $item = null, array $categories = []);
}
