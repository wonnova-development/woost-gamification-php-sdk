<?php
namespace Wonnova\SDK\Auth;

/**
 * Class Credentials
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class Credentials implements CredentialsInterface
{
    /**
     * @var string
     */
    protected $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return ['key' => $this->getKey()];
    }
}
