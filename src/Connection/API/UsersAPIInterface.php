<?php
namespace Wonnova\SDK\Connection\API;

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
    const CREATE_USER_ROUTE = '/users/%userId%';

    /**
     * Returns users list
     *
     * @return User[]
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
}
