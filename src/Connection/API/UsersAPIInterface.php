<?php
namespace Wonnova\SDK\Connection\API;

use Doctrine\Common\Collections\Collection;
use Wonnova\SDK\Model\User;

/**
 * Interface UsersAPIInterface
 * @author Wonnova
 * @link http://www.wonnova.com
 */
interface UsersAPIInterface
{
    const USERS_ROUTE = '/users';
    const USER_ROUTE = '/users?userId=%userId%';
    const UPDATE_USER_ROUTE = '/users/%userId%';
    const USER_NOTIFICATIONS_ROUTE = '/users/%userId%/notifications';
    const USER_BADGES_ROUTE = '/users/%userId%/badges';

    /**
     * Returns users list
     *
     * @return Collection<User>
     */
    public function getUsers();

    /**
     * Returns information about certain user
     *
     * @param $userId
     * @return User
     */
    public function getUser($userId);

    /**
     * Creates provided user
     *
     * @param User $user
     */
    public function createUser(User $user);

    /**
     * Updates provided user
     *
     * @param User $user
     */
    public function updateUser(User $user);

    /**
     * Returns the list of pending notifications for a user
     *
     * @param User|string $user A User model or userId
     * @return Collection<Notification>
     */
    public function getUserNotifications($user);

    /**
     * Returns the list of badges that certain user has won
     *
     * @param User|string $user A User model or userId
     * @return Collection<Notification>
     */
    public function getUserBadges($user);
}
