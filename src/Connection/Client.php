<?php
namespace Wonnova\SDK\Connection;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use GuzzleHttp\Exception\ServerException;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Wonnova\SDK\Auth\CredentialsInterface;
use Wonnova\SDK\Auth\TokenInterface;
use Wonnova\SDK\Common\Headers;
use Wonnova\SDK\Common\URIUtils;
use Wonnova\SDK\Model\User;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;

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
            if ($e->getCode() === 401) {
                $this->token = null;
                return $this->connect($method, $route, $options);
            }

            // TODO Handle other errors

            throw new \Wonnova\SDK\Exception\ClientException(
                sprintf('There was a client error processing a request to "%s" with method "%s"', $route, $method),
                $e
            );
        } catch (ServerException $e) {
            throw new \Wonnova\SDK\Exception\ServerException(
                sprintf('There was a server error processing a request to "%s" with method "%s"', $route, $method),
                $e
            );
        }
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
        // TODO: Implement getUser() method.
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
