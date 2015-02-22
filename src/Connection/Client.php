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
use Wonnova\SDK\Exception\UnauthorizedException;
use Wonnova\SDK\Model\Achievement;
use Wonnova\SDK\Model\Badge;
use Wonnova\SDK\Model\Notification;
use Wonnova\SDK\Model\QuestStep;
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
     * @throws \GuzzleHttp\Exception\ClientException
     */
    private function connect($method, $route, array $options = [])
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
            switch ($e->getCode()) {
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
                        $e->getCode(),
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
                        $e->getCode(),
                        $e
                    );
                case 404:
                    throw new NotFoundException(
                        sprintf('Route "%s" with method "%s" was not found', $route, $method),
                        $e->getCode(),
                        $e
                    );
                default:
                    throw $e;
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
        return $this->getUserSubresourceList(URIUtils::parseUri(self::USER_NOTIFICATIONS_ROUTE, [
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
        return $this->getUserSubresourceList(URIUtils::parseUri(self::USER_BADGES_ROUTE, [
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

        return $this->getUserSubresourceList(URIUtils::parseUri(self::USER_ACHIEVEMENTS_ROUTE, [
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
        return $this->getUserSubresourceList(URIUtils::parseUri(self::USER_QUEST_PROGRESS_ROUTE, [
            'userId' => $userId,
            'questCode' => $questCode
        ]), 'questSteps', 'Wonnova\SDK\Model\QuestStep');
    }

    /**
     * Fetches a route and maps a user's subresource list of models
     *
     * @param $route
     * @param $resourceKey
     * @param $resourceClass
     * @return ArrayCollection
     */
    protected function getUserSubresourceList($route, $resourceKey, $resourceClass)
    {
        $response = $this->connect('GET', $route);
        $contents = $this->serializer->deserialize($response->getBody()->getContents(), 'array', 'json');
        $contents = $this->serializer->serialize($contents[$resourceKey], 'json');
        return new ArrayCollection($this->serializer->deserialize(
            $contents,
            sprintf('array<%s>', $resourceClass),
            'json'
        ));
    }
}
