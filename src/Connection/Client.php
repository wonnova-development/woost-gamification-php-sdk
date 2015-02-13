<?php
namespace Wonnova\SDK\Connection;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Wonnova\SDK\Auth\CredentialsInterface;
use Wonnova\SDK\Auth\TokenInterface;
use Wonnova\SDK\Common\URIUtils;
use Wonnova\SDK\Model\User;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Class Client
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class Client implements ClientInterface
{
    const AUTH_ROUTE = '/auth';
    const USER_AGENT = 'wonnova-php-sdk';

    /**
     * @var CredentialsInterface
     */
    protected $credentials;
    /**
     * @var GuzzleClient
     */
    protected $httpClient;
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
     * @param GuzzleClient $httpClient
     * @param Serializer $serializer
     * @param CredentialsInterface $credentials
     * @param $language
     */
    public function __construct(
        GuzzleClient $httpClient,
        Serializer $serializer,
        CredentialsInterface $credentials,
        $language
    ) {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->credentials = $credentials;
        $this->language = $language;
    }

    /**
     * Creates a new Client with provided Credentials and language
     *
     * @param CredentialsInterface $credentials
     * @param string $language
     * @return Client
     */
    public static function factory(CredentialsInterface $credentials, $language = 'es')
    {
        return new Client(
            new GuzzleClient([
                'base_url' => sprintf('%s/rest', URIUtils::HOST),
                'defaults' => [
                    'headers' => [
                        'User-Agent' => self::USER_AGENT
                    ]
                ]
            ]),
            new Serializer([], [new JsonEncoder()]),
            $credentials,
            $language
        );
    }

    protected function connect()
    {

    }

    /**
     * Returns users list
     *
     * @return User[]
     */
    public function getUsers()
    {
        $response = $this->send($this->createRequest('GET', self::USERS_ROUTE));
        return $this->mapper->mapArray($response->getBody(), [], '');
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
