<?php
namespace Wonnova\SDK\Auth;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Token
 * @author Wonnova
 * @link http://www.wonnova.com
 *
 * @JMS\AccessType("public_method")
 */
class Token implements TokenInterface
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\Accessor(getter="getAccessToken", setter="setAccessToken")
     */
    private $token;

    /**
     * Returns auth token
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->token;
    }

    public function setAccessToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [self::HEADER_KEY => $this->getAccessToken()];
    }
}
