<?php
namespace Wonnova\SDK\Connection;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use GuzzleHttp\Exception\ServerException;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Wonnova\SDK\Auth\CredentialsInterface;
use Wonnova\SDK\Auth\TokenInterface;
use Wonnova\SDK\Common\Headers;
use Wonnova\SDK\Common\URIUtils;
use Wonnova\SDK\Exception\InvalidRequestException;
use Wonnova\SDK\Exception\NotFoundException;
use Wonnova\SDK\Model\User;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use Wonnova\SDK\Serializer\DateTimeHandler;

/**
 * Class Client
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class Client extends GuzzleClient implements ClientInterface
{
    const AUTH_ROUTE = '/auth';
    const USER_AGENT = 'wonnova-php-sdk';

    /**
     * @var CredentialsInterface
     */
    protected $credentials;
    /**
     * @var Serializer
     */
    protected $serializer;
    /**
     * @var TokenInterface
     */
    protected $token;
    /**
     * @var string
     */
    protected $language;

    /**
     * @param CredentialsInterface $credentials
     * @param string $language
     * @param null $baseUrl
     */
    public function __construct(CredentialsInterface $credentials, $language = 'es', $baseUrl = null)
    {
        parent::__construct([
            'base_url' => $baseUrl ?: URIUtils::HOST,
            'defaults' => [
                'headers' => [
                    'User-Agent' => self::USER_AGENT
                ]
            ]
        ]);
        $this->serializer = SerializerBuilder::create()
            ->setPropertyNamingStrategy(new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy()))
            ->configureHandlers(function (HandlerRegistry $registry) {
                $registry->registerSubscribingHandler(new DateTimeHandler());
            })
            ->build();
        $this->credentials = $credentials;
        $this->language = $language;

        // This makes annotations autoloading work with existing annotation classes
        AnnotationRegistry::registerLoader('class_exists');
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
                    $this->resetToken();
                    // FIXME Fix possible inifnite loop here
                    return $this->connect($method, $route, $options);
                    break;
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
        // TODO Clear token cache
    }

    /**
     * Performs authentication caching the auth token
     */
    private function authenticate()
    {
        $response = $this->send($this->createRequest('POST', URIUtils::parseUri(self::AUTH_ROUTE), [
            'json' => [
                'key' => $this->credentials->getKey()
            ]
        ]));
        $this->token = $this->serializer->deserialize(
            $response->getBody()->getContents(),
            'Wonnova\SDK\Auth\Token',
            'json'
        );
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
     * @return ArrayCollection<User>
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
        return $this->serializer->deserialize(
            $contents,
            'Wonnova\SDK\Model\User',
            'json'
        );
    }

    /**
     * Creates provided user
     *
     * @param User $user
     */
    public function createUser(User $user)
    {
        // TODO: Implement createUser() method.
    }

    /**
     * Updates provided user
     *
     * @param User $user
     */
    public function updateUser(User $user)
    {
        // TODO: Implement updateUser() method.
    }
}
