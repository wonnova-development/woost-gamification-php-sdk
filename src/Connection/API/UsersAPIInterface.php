<?php
namespace Wonnova\SDK\Connection\API;

use Doctrine\Common\Collections\Collection;
use Wonnova\SDK\Model\Achievement;
use Wonnova\SDK\Model\Badge;
use Wonnova\SDK\Model\Notification;
use Wonnova\SDK\Model\Quest;
use Wonnova\SDK\Model\QuestStep;
use Wonnova\SDK\Model\Update;
use Wonnova\SDK\Model\User;

/**
 * Interface UsersAPIInterface
 * @author Wonnova
 * @link http://www.wonnova.com
 */
interface UsersAPIInterface
{
    const USERS_ROUTE                   = '/rest/users';
    const UPDATE_USER_ROUTE             = '/rest/users/%userId%';
    const USER_NOTIFICATIONS_ROUTE      = '/rest/users/%userId%/notifications';
    const USER_BADGES_ROUTE             = '/rest/users/%userId%/badges';
    const USER_ACHIEVEMENTS_ROUTE       = '/rest/users/%userId%/achievements';
    const USER_QUEST_PROGRESS_ROUTE     = '/rest/users/%userId%/quests/%questCode%/progress';
    const USER_QUESTS_STATUS_ROUTE      = '/rest/users/%userId%/quests/status';
    const USER_ABOUT_ROUTE              = '/rest/users/%userId%/about';
    const USER_ACTION_OCCURRENCES_ROUTE = '/rest/users/%userId%/actions/%actionCode%/occurrences';
    const USER_LAST_UPDATES             = '/rest/users/%userId%/last-updates';

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

    /**
     * Returns all the quests with which a user is involved with, including the list of their steps
     *
     * @param User|string $user A User model or userId
     * @return Collection|Quest[]
     */
    public function getUserStatusInQuests($user);

    /**
     * Returns a partial model of certain user defined by its userId
     * By calling this method, only the properties username, fullName, avatar and score of the User will be popullated,
     * as well as the provided userId
     *
     * @param $userId
     * @return User
     */
    public function getUserData($userId);

    /**
     * Returns the number of times a user has performed certain action
     *
     * @param User|string $user A User model or userId
     * @param string $actionCode
     * @return int
     */
    public function getUserActionOccurrences($user, $actionCode);

    /**
     * Returns the list of most recent updates for certain user
     *
     * @param User|string $user A User model or userId
     * @param int $maxCount
     * @return Collection|Update[]
     */
    public function getUserLastUpdates($user, $maxCount = null);
}
