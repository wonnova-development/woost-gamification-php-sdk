<?php
namespace Wonnova\SDK\Connection;

use GuzzleHttp\Exception\ServerException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
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
     * @param $language
     */
    public function __construct(CredentialsInterface $credentials, $language = 'es')
    {
        parent::__construct([
            'base_url' => URIUtils::HOST,
            'defaults' => [
                'headers' => [
                    'User-Agent' => self::USER_AGENT
                ]
            ]
        ]);
        $this->serializer = new Serializer([new GetSetMethodNormalizer()], [new JsonEncoder()]);
        $this->credentials = $credentials;
        $this->language = $language;
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
    protected function connect($method, $route, array $options = [])
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
    protected function authenticate()
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
        $options['headers'][Headers::TOKEN_HEADER] = $this->token->getToken();

        return $options;
    }

    /**
     * Returns users list
     *
     * @return User[]
     */
    public function getUsers()
    {
        $response = $this->connect('GET', URIUtils::parseUri(self::USERS_ROUTE));
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
