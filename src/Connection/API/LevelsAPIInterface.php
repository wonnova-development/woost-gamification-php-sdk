<?php
namespace Wonnova\SDK\Connection\API;

use Doctrine\Common\Collections\Collection;
use Wonnova\SDK\Model\Level;
use Wonnova\SDK\Model\User;

/**
 * Interface LevelsAPIInterface
 * @author Wonnova
 * @link http://www.wonnova.com
 */
interface LevelsAPIInterface
{
    const LEVELS_ROUTE      = '/rest/levels';
    const USER_LEVEL_ROUTE  = '/rest/levels/users/%userId%/scenarios/%scenarioCode%';
    const LEVEL_IMAGE_ROUTE = '/rest/levels/%levelId%/image';

    /**
     * Returns the list of previously created Levels
     *
     * @return Collection|Level[]
     */
    public function getLevels();

    /**
     * Returns the level of a user in certain scenario
     *
     * @param User|string $user A User model or userId
     * @param string $scenarioCode
     * @return Level
     */
    public function getUserLevelInScenario($user, $scenarioCode);
}
