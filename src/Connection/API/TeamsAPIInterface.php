<?php
namespace Wonnova\SDK\Connection\API;

use Doctrine\Common\Collections\Collection;
use Wonnova\SDK\Model\Team;
use Wonnova\SDK\Model\User;

/**
 * Interface TeamsAPIInterface
 * @author Wonnova
 * @link http://www.wonnova
 */
interface TeamsAPIInterface
{
    const TEAMS_LEADERBOARD_ROUTE = '/teams/leaderboard?userId=%userId%&maxCount=%maxCount%';

    /**
     * Returns the list of top teams ordered by total score of their members
     * If a user is provided, the list will include the team of that user, even if it is not in the top list
     *
     * @param int|null $maxCount Maximum number of results
     * @param User|string|null $user A User model or userId
     * @return Collection|Team[]
     */
    public function getTeamsLeaderboard($maxCount = null, $user = null);
}
