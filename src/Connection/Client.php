<?php
namespace Wonnova\SDK\Connection;

use Wonnova\SDK\Auth\TokenInterface;
use Wonnova\SDK\Common\URIUtils;
use Wonnova\SDK\Model\User;
use GuzzleHttp\Client as GuzzleClient;

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
    public function __construct(CredentialsInterface $credentials, $language)
    {
        parent::__construct([
            'base_url' => sprintf('%s/rest', URIUtils::HOST),
            'defaults' => [
                'headers' => [
                    'User-Agent' => self::USER_AGENT
                ]
            ]
        ]);

        $this->credentials = $credentials;
        $this->language = $language;
    }

    protected function connect()
    {

    }

    protected function performRequest()
    {

    }

    /**
     * Returns users list
     *
     * @return User[]
     */
    public function getUsers()
    {
        // TODO: Implement getUsers() method.
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
