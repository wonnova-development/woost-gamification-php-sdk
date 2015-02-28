<?php
namespace Wonnova\SDK\Connection;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use GuzzleHttp\Exception\ServerException;
use JMS\Serializer\Serializer;
use Wonnova\SDK\Auth\CredentialsInterface;
use Wonnova\SDK\Auth\Token;
use Wonnova\SDK\Auth\TokenInterface;
use Wonnova\SDK\Common\Headers;
use Wonnova\SDK\Common\URIUtils;
use Wonnova\SDK\Exception\InvalidArgumentException;
use Wonnova\SDK\Exception\InvalidRequestException;
use Wonnova\SDK\Exception\NotFoundException;
use Wonnova\SDK\Exception\RuntimeException;
use Wonnova\SDK\Exception\UnauthorizedException;
use Wonnova\SDK\Model\Achievement;
use Wonnova\SDK\Model\Badge;
use Wonnova\SDK\Model\Item;
use Wonnova\SDK\Model\Level;
use Wonnova\SDK\Model\Notification;
use Wonnova\SDK\Model\Quest;
use Wonnova\SDK\Model\QuestStep;
use Wonnova\SDK\Model\Team;
use Wonnova\SDK\Model\User;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use Wonnova\SDK\Serializer\SerializerFactory;

/**
 * Class Client
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class Client extends GuzzleClient implements ClientInterface
{
    const AUTH_ROUTE = '/auth';
    const USER_AGENT = 'wonnova-php-sdk';
    const TOKEN_KEY = 'wonnova_auth_token';

    /**
     * @var CredentialsInterface
     */
    protected $credentials;
    /**
     * @var string
     */
    protected $language;
    /**
     * @var Cache
     */
    protected $cache;
    /**
     * @var Serializer
     */
    protected $serializer;
    /**
     * @var TokenInterface
     */
    protected $token;

    /**
     * @param CredentialsInterface $credentials
     * @param string $language
     * @param Cache $cache
     * @param null $baseUrl
     */
    public function __construct(
        CredentialsInterface $credentials,
        $language = 'es',
        Cache $cache = null,
        $baseUrl = null
    ) {
        parent::__construct([
            'base_url' => $baseUrl ?: URIUtils::HOST,
            'defaults' => [
                'headers' => [
                    'User-Agent' => self::USER_AGENT
                ]
            ]
        ]);

        $this->serializer = SerializerFactory::create();
        $this->credentials = $credentials;
        $this->language = $language;
        $this->cache = $cache ?: new FilesystemCache(sys_get_temp_dir());

        // Initialize the token if it exists in cache
        if ($this->cache->contains(self::TOKEN_KEY)) {
            $this->token = new Token();
            $this->token->setAccessToken($this->cache->fetch(self::TOKEN_KEY));
        }

    }

    /**
     * Performs a connection to defined endpoint with defined options
     *
     * @param $method
     * @param $route
     * @param array $options
     * @return \GuzzleHttp\Message\ResponseInterface
     * @throws \Wonnova\SDK\Exception\ServerException
     * @throws \Wonnova\SDK\Exception\InvalidRequestException
     * @throws \Wonnova\SDK\Exception\NotFoundException
     * @throws \Wonnova\SDK\Exception\RuntimeException
     */
    public function connect($method, $route, array $options = [])
    {
        try {
            // Perform authentication if token has not been set yet
            if (! isset($this->token)) {
                $this->authenticate();
            }

            // Add the language and token headers
            $options = $this->processOptionsWithDefaults($options);

            return $this->send($this->createRequest($method, $route, $options));
        } catch (ClientException $e) {
            $code = $e->getCode();

            switch ($code) {
                case 401: // Token not valid. Reconect
                    $message = json_decode($e->getResponse()->getBody()->getContents(), true);

                    // If the server returned an INVALID_TOKEN response, reconnect
                    if ($message['error'] === 'INVALID_TOKEN') {
                        $this->resetToken();
                        return $this->connect($method, $route, $options);
                        break;
                    }

                    throw new UnauthorizedException(
                        sprintf(
                            'Unauthorized request to "%s" with method "%s" and response message "%s"',
                            $route,
                            $method,
                            isset($message['message']) ? $message['message'] : ''
                        ),
                        $code,
                        $e
                    );
                case 400:
                    $message = json_decode($e->getResponse()->getBody()->getContents(), true);
                    throw new InvalidRequestException(
                        sprintf(
                            'Invalid request to "%s" with method "%s" and response message "%s"',
                            $route,
                            $method,
                            $message['message']
                        ),
                        $code,
                        $e
                    );
                case 404:
                    throw new NotFoundException(
                        sprintf('Route "%s" with method "%s" was not found', $route, $method),
                        $code,
                        $e
                    );
                default:
                    throw new RuntimeException(
                        sprintf(
                            'Unexpected error occurred whith request to route "%s" with method "%s"',
                            $route,
                            $method
                        ),
                        $code,
                        $e
                    );
            }
        } catch (ServerException $e) {
            throw new \Wonnova\SDK\Exception\ServerException(
                sprintf('There was a server error processing a request to "%s" with method "%s"', $route, $method),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Resets the token so that it can be reinitialized.
     * This has to be used when current token has expired or is invalid
     */
    private function resetToken()
    {
        $this->token = null;
        $this->cache->delete(self::TOKEN_KEY);
    }

    /**
     * Performs authentication caching the auth token
     */
    private function authenticate()
    {
        $response = $this->send($this->createRequest('POST', URIUtils::parseUri(self::AUTH_ROUTE), [
            'json' => $this->credentials->toArray()
        ]));
        $this->token = $this->serializer->deserialize(
            $response->getBody()->getContents(),
            'Wonnova\SDK\Auth\Token',
            'json'
        );
        $this->cache->save(self::TOKEN_KEY, $this->token->getAccessToken());
    }

    /**
     * @param array $options
     * @return array
     */
    protected function processOptionsWithDefaults(array $options)
    {
        $options['headers'] = isset($options['headers']) ? $options['headers'] : [];

        $options['headers'][Headers::LANGUAGE_HEADER] = $this->language;
        $options['headers'][Headers::TOKEN_HEADER] = $this->token->getAccessToken();

        return $options;
    }

    /**
     * Fetches a route and maps a resource list of models under provided key
     *
     * @param $route
     * @param $resourceKey
     * @param $resourceClass
     * @return ArrayCollection
     */
    protected function getResourceCollection($route, $resourceKey, $resourceClass)
    {
        $response = $this->connect('GET', $route);
        $contents = $this->serializer->deserialize($response->getBody()->getContents(), 'array', 'json');
        return new ArrayCollection($this->serializer->deserialize(
            $contents[$resourceKey],
            sprintf('array<%s>', $resourceClass),
            'array'
        ));
    }

    /**
     * Returns users list
     *
     * @return Collection|User[]
     */
    public function getUsers()
    {
        $response = $this->connect('GET', URIUtils::parseUri(self::USERS_ROUTE));
        $contents = $response->getBody()->getContents();
        return new ArrayCollection($this->serializer->deserialize(
            $contents,
            'array<Wonnova\SDK\Model\User>',
            'json'
        ));
    }

    /**
     * Returns information about certain user
     *
     * @param $userId
     * @return User
     */
    public function getUser($userId)
    {
        $response = $this->connect('GET', URIUtils::parseUri(self::USER_ROUTE, [
            'userId' => $userId
        ]));
        $contents = $response->getBody()->getContents();
        return $this->serializer->deserialize($contents, 'Wonnova\SDK\Model\User', 'json');
    }

    /**
     * Creates provided user
     *
     * @param User $user
     */
    public function createUser(User $user)
    {
        $response = $this->connect('POST', URIUtils::parseUri(self::USERS_ROUTE), [
            'json' => $user
        ]);
        $contents = $response->getBody()->getContents();
        $userData = $this->serializer->deserialize($contents, 'array', 'json');
        // The server will return the user ID. Set it to the model
        $user->setUserId($userData['userId']);
    }

    /**
     * Updates provided user
     *
     * @param User $user
     */
    public function updateUser(User $user)
    {
        $userId = $user->getUserId();
        if (empty($userId)) {
            throw new InvalidArgumentException('Provided user has an empty userId.');
        }

        $response = $this->connect('PUT', URIUtils::parseUri(self::UPDATE_USER_ROUTE, [
            'userId' => $userId
        ]), [
            'json' => $user
        ]);
        $contents = $response->getBody()->getContents();
        // The server will return the user data. Refresh the model
        $user->fromArray($this->serializer->deserialize($contents, 'array', 'json'));
    }

    /**
     *
     *
     * @param User|string $user A User model or userId
     * @return Collection|Notification[]
     */
    public function getUserNotifications($user)
    {
        $userId = $user instanceof User ? $user->getUserId() : $user;
        return $this->getResourceCollection(URIUtils::parseUri(self::USER_NOTIFICATIONS_ROUTE, [
            'userId' => $userId
        ]), 'notifications', 'Wonnova\SDK\Model\Notification');
    }

    /**
     * Returns the list of badges that certain user has won
     *
     * @param User|string $user A User model or userId
     * @return Collection|Badge[]
     */
    public function getUserBadges($user)
    {
        $userId = $user instanceof User ? $user->getUserId() : $user;
        return $this->getResourceCollection(URIUtils::parseUri(self::USER_BADGES_ROUTE, [
            'userId' => $userId
        ]), 'badges', 'Wonnova\SDK\Model\Badge');
    }

    /**
     * Returns the number of achievements of each type for certain user
     *
     * @param User|string $user A User model or userId
     * @param array|string $types List of types in a comma-separated string or array.
     *          All the types will be returned by default
     * @return Collection|Achievement[]
     */
    public function getUserAchievements($user, $types = [])
    {
        $userId = $user instanceof User ? $user->getUserId() : $user;
        $types = empty($types) ? Achievement::getAllTypesList() : $types;
        $types = is_array($types) ? implode(',', $types) : $types;

        return $this->getResourceCollection(URIUtils::parseUri(self::USER_ACHIEVEMENTS_ROUTE, [
            'userId' => $userId,
            'types' => $types
        ]), 'achievements', 'Wonnova\SDK\Model\Achievement');
    }

    /**
     * Returns the list of steps in a quest telling if certain user has already completed them
     *
     * @param User|string $user A User model or userId
     * @param string $questCode
     * @return Collection|QuestStep[]
     */
    public function getUserProgressInQuest($user, $questCode)
    {
        $userId = $user instanceof User ? $user->getUserId() : $user;
        return $this->getResourceCollection(URIUtils::parseUri(self::USER_QUEST_PROGRESS_ROUTE, [
            'userId' => $userId,
            'questCode' => $questCode
        ]), 'questSteps', 'Wonnova\SDK\Model\QuestStep');
    }

    /**
     * Returns the list of created quests
     *
     * @return Collection|Quest[]
     */
    public function getQuests()
    {
        return $this->getResourceCollection(
            URIUtils::parseUri(self::QUESTS_ROUTE),
            'quests',
            'Wonnova\SDK\Model\Quest'
        );
    }

    /**
     * Returns all the quests with which a user is involved with, including the list of their steps
     *
     * @param User|string $user A User model or userId
     * @return Collection|Quest[]
     */
    public function getUserStatusInQuests($user)
    {
        $userId = $user instanceof User ? $user->getUserId() : $user;
        return $this->getResourceCollection(URIUtils::parseUri(self::USER_QUESTS_STATUS_ROUTE, [
            'userId' => $userId
        ]), 'quests', 'Wonnova\SDK\Model\Quest');
    }

    /**
     * @return Collection|Level[]
     */
    public function getLevels()
    {
        return $this->getResourceCollection(
            URIUtils::parseUri(self::LEVELS_ROUTE),
            'levels',
            'Wonnova\SDK\Model\Level'
        );
    }

    /**
     * Returns the level of a user in certain scenario
     *
     * @param User|string $user A User model or userId
     * @param string $scenarioCode
     * @return Level
     */
    public function getUserLevelInScenario($user, $scenarioCode)
    {
        $userId = $user instanceof User ? $user->getUserId() : $user;

        $response = $this->connect('GET', URIUtils::parseUri(self::USER_LEVEL_ROUTE, [
            'userId' => $userId,
            'scenarioCode' => $scenarioCode
        ]));
        $contents = $response->getBody()->getContents();
        $contents = $this->serializer->deserialize($contents, 'array', 'json');
        return $this->serializer->deserialize($contents['level'], 'Wonnova\SDK\Model\Level', 'array');
    }

    /**
     * Returns the list of top teams ordered by total score of their members
     * If a user is provided, the list will include the team of that user, even if it is not in the top list
     *
     * @param int|null $maxCount Maximum number of results
     * @param User|string|null $user A User model or userId
     * @return Collection|Team[]
     */
    public function getTeamsLeaderboard($maxCount = null, $user = null)
    {
        $routeParams = [
            'userId' => '',
            'maxCount' => ''
        ];
        if (is_int($maxCount)) {
            $routeParams['maxCount'] = $maxCount;
        }
        if (isset($user)) {
            $routeParams['userId'] = $user instanceof User ? $user->getUserId() : $user;
        }

        return $this->getResourceCollection(
            URIUtils::parseUri(self::TEAMS_LEADERBOARD_ROUTE, $routeParams),
            'scores',
            'Wonnova\SDK\Model\Team'
        );
    }

    /**
     * Returns the top rated items
     *
     * @param int $maxCount
     * @return Collection|Item[]
     */
    public function getItemsLeaderboard($maxCount = 6)
    {
        return $this->getResourceCollection(URIUtils::parseUri(self::ITEMS_LEADERBOARD_ROUTE, [
            'maxCount' => $maxCount
        ]), 'leaderboard', 'Wonnova\SDK\Model\Item');
    }

    /**
     * Rates an item increasing its score and setting the rate from certain user.
     *
     * @param User|string $user a User model or userId
     * @param Item|string $item an Item model or itemId
     * @param int $score
     * @return Item
     */
    public function rateItem($user, $item, $score = 0)
    {
        $data = [
            'userId' => $user instanceof User ? $user->getUserId() : $user,
            'points' => $score,
            'item' => $item instanceof Item ? $item->toArray() : [
                'id' => $item
            ]
        ];
        $response = $this->connect('POST', URIUtils::parseUri(self::ITEM_RATE_ROUTE), [
            'json' => $data
        ]);
        $contents = $response->getBody()->getContents();
        $itemData = $this->serializer->deserialize($contents, 'array', 'json');

        // If an Item wasn't provided, create a new Item instance
        if (! $item instanceof Item) {
            $item = new Item();
        }

        // Refresh the Item's data and return it
        $item->fromArray($itemData['item']);
        return $item;
    }

    /**
     * Deletes certain item
     *
     * @param Item|string $item an Item model or itemId
     * @return void
     */
    public function deleteItem($item)
    {
        $itemId = $item instanceof Item ? $item->getItemId() : $item;
        $this->connect('DELETE', URIUtils::parseUri(self::ITEM_ROUTE, [
            'itemId' => $itemId
        ]));
    }

    /**
     * Resets an item's score to zero
     *
     * @param Item|string $item an Item model or itemId
     * @return void
     */
    public function resetItemScore($item)
    {
        $itemId = $item instanceof Item ? $item->getItemId() : $item;
        $this->connect('PUT', URIUtils::parseUri(self::ITEM_RESET_SCORE_ROUTE, [
            'itemId' => $itemId
        ]));
    }

    /**
     * Returns a partial model of certain user defined by its userId
     * By calling this method, only the properties username, fullName, avatar and score of the User will be popullated,
     * as well as the provided userId
     *
     * @param $userId
     * @return User
     */
    public function getUserData($userId)
    {
        $response = $this->connect('GET', URIUtils::parseUri(self::USER_ABOUT_ROUTE, [
            'userId' => $userId
        ]));
        $contents = $response->getBody()->getContents();
        $responseData = $this->serializer->deserialize($contents, 'array', 'json');

        // Create the User model to be returned and populate it
        $user = new User();
        $user->fromArray($responseData['userData']);
        $user->setUserId($userId);
        return $user;
    }

    /**
     * Performs an action notification from certain user
     *
     * @param User|string $user A User model or userId
     * @param string $actionCode
     * @param Item|string $item An Item model or itemId
     * @param array $categories
     * @return void
     */
    public function notifyAction($user, $actionCode, $item = null, array $categories = [])
    {
        // Prepare request body
        $requestData = [
            'userId' => $user instanceof User ? $user->getUserId() : $user,
            'actionCode' => $actionCode
        ];
        if (isset($item)) {
            $requestData['item'] = $item instanceof Item ? $item->toArray() : [
                'id' => $item
            ];
        }
        if (! empty($categories)) {
            $requestData['categories'] = $categories;
        }

        // Perform request
        $this->connect('POST', URIUtils::parseUri(self::ACTION_NOTIFICATION_ROUTE), [
            'json' => $requestData
        ]);
    }

    /**
     * Performs an interaction notification between two users
     *
     * @param User|string $user A User model or userId
     * @param User|string $targetUser A User model or userId
     * @param string $interactionCode
     * @param Item|null $item An Item model or itemId
     * @param array $categories
     * @return void
     */
    public function notifyInteraction($user, $targetUser, $interactionCode, $item = null, array $categories = [])
    {
        // Prepare request body
        $requestData = [
            'userId' => $user instanceof User ? $user->getUserId() : $user,
            'targetUserId' => $targetUser instanceof User ? $targetUser->getUserId() : $targetUser,
            'interactionCode' => $interactionCode
        ];
        if (isset($item)) {
            $requestData['item'] = $item instanceof Item ? $item->toArray() : [
                'id' => $item
            ];
        }
        if (! empty($categories)) {
            $requestData['categories'] = $categories;
        }

        // Perform request
        $this->connect('POST', URIUtils::parseUri(self::INTERACTION_NOTIFICATION_ROUTE), [
            'json' => $requestData
        ]);
    }

    /**
     * Returns the number of times a user has performed certain action
     *
     * @param User|string $user A User model or userId
     * @param string $actionCode
     * @return int
     */
    public function getUserActionOccurrences($user, $actionCode)
    {
        $response = $this->connect('GET', URIUtils::parseUri(self::USER_ACTION_OCCURRENCES_ROUTE, [
            'userId' => $user instanceof User ? $user->getUserId() : $user,
            'actionCode' => $actionCode
        ]));
        $contents = $response->getBody()->getContents();
        return $this->serializer->deserialize($contents, 'array', 'json')['occurrences'];
    }

    /**
     * Returns information about the status of an interactionaccording to its limits
     *
     * @param User|string $user A User model or userId
     * @param User|string $targetUser A User model or userId
     * @param string $interactionCode
     * @param Item|string $item An Item model or itemId
     * @return array
     */
    public function getInteractionStatus($user, $targetUser, $interactionCode, $item)
    {
        // Prepare request body
        $requestData = [
            'user' => $user instanceof User ? $user->getUserId() : $user,
            'targetUser' => [
                'id' => $targetUser instanceof User ? $targetUser->getUserId() : $targetUser
            ],
            'interactionCode' => $interactionCode,
            'item' => $item instanceof Item ? $item->toArray() : [
                'id' => $item
            ]
        ];

        $response = $this->connect('POST', URIUtils::parseUri(self::INTERACTION_STATUS_ROUTE), [
            'json' => $requestData
        ]);
        $contents = $response->getBody()->getContents();
        $responseData = $this->serializer->deserialize($contents, 'array', 'json');

        // Process response properties
        unset($responseData['status']);
        if (! isset($responseData['score'])) {
            $responseData['score'] = 0;
        }

        return $responseData;
    }
}
