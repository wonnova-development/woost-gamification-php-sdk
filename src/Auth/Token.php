<?php
namespace Wonnova\SDK\Auth;

/**
 * Class Token
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class Token implements TokenInterface
{
    private $tokenKey;

    public function __construct($tokenKey)
    {
        $this->tokenKey = $tokenKey;
    }

    /**
     * Returns auth token
     *
     * @return string
     */
    public function getAuthToken()
    {
        return $this->tokenKey;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [self::HEADER_KEY => $this->getAuthToken()];
    }
}
