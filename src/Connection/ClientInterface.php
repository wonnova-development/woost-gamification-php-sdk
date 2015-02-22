<?php
namespace Wonnova\SDK\Connection;

use Wonnova\SDK\Connection\API\ActionsAPIInterface;
use Wonnova\SDK\Connection\API\InteractionsAPIInterface;
use Wonnova\SDK\Connection\API\ItemsAPIInterface;
use Wonnova\SDK\Connection\API\LevelsAPIInterface;
use Wonnova\SDK\Connection\API\QuestsAPIInterface;
use Wonnova\SDK\Connection\API\TeamsAPIInterface;
use Wonnova\SDK\Connection\API\UsersAPIInterface;

/**
 * Interface ClientInterface
 * @author Wonnova
 * @link http://www.wonnova.com
 */
interface ClientInterface extends
    UsersAPIInterface,
    ActionsAPIInterface,
    InteractionsAPIInterface,
    ItemsAPIInterface,
    LevelsAPIInterface,
    QuestsAPIInterface,
    TeamsAPIInterface
{

}
