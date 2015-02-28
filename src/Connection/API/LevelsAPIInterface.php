<?php
namespace Wonnova\SDK\Connection\API;

use Doctrine\Common\Collections\Collection;
use Wonnova\SDK\Model\Level;

/**
 * Interface LevelsAPIInterface
 * @author Wonnova
 * @link http://www.wonnova.com
 */
interface LevelsAPIInterface
{
    const LEVELS_ROUTE = '/levels';

    /**
     * @return Collection|Level[]
     */
    public function getLevels();
}
