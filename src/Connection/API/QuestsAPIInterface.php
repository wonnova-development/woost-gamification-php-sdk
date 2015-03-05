<?php
namespace Wonnova\SDK\Connection\API;

use Doctrine\Common\Collections\Collection;
use Wonnova\SDK\Model\Quest;

/**
 * Interface QuestsAPIInterface
 * @author Wonnova
 * @link http://www.wonnova.com
 */
interface QuestsAPIInterface
{
    const QUESTS_ROUTE = '/rest/quests';

    /**
     * Returns the list of created quests
     *
     * @return Collection|Quest[]
     */
    public function getQuests();
}
