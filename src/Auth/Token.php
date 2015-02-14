<?php
namespace Wonnova\SDK\Auth;

/**
 * Class Token
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class Token implements TokenInterface
{
    /**
     * @var string
     */
    private $token;

    public function __construct($token = '')
    {
        $this->token = $token;
    }

    /**
     * Returns auth token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [self::HEADER_KEY => $this->getToken()];
    }
}
