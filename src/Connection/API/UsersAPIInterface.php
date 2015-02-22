<?php
namespace Wonnova\SDK\Connection\API;

use Doctrine\Common\Collections\Collection;
use Wonnova\SDK\Model\Achievement;
use Wonnova\SDK\Model\Badge;
use Wonnova\SDK\Model\Notification;
use Wonnova\SDK\Model\QuestStep;
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
    const USER_ACHIEVEMENTS_ROUTE = '/users/%userId%/achievements?types=%types%';
    const USER_QUEST_PROGRESS_ROUTE = '/users/%userId%/quests/%questCode%/progress';

    /**
     * Returns users list
     *
     * @return Collection|User[]
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
     * @return Collection|Notification[]
     */
    public function getUserNotifications($user);

    /**
     * Returns the list of badges that certain user has won
     *
     * @param User|string $user A User model or userId
     * @return Collection|Badge[]
     */
    public function getUserBadges($user);

    /**
     * Returns the number of achievements of each type for certain user
     *
     * @param User|string $user A User model or userId
     * @param array|string $types List of types in a comma-separated string or array.
     *          All the types will be returned by default
     * @return Collection|Achievement[]
     */
    public function getUserAchievements($user, $types = []);

    /**
     * Returns the list of steps in a quest telling if certain user has already completed them
     *
     * @param User|string $user A User model or userId
     * @param string $questCode
     * @return Collection|QuestStep[]
     */
    public function getUserProgressInQuest($user, $questCode);
}
