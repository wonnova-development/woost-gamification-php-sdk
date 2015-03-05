<?php
namespace Wonnova\SDK\Auth;

use Wonnova\SDK\Exception\InvalidArgumentException;

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

    /**
     * @param string|array $key
     */
    public function __construct($key)
    {
        if (is_array($key)) {
            if (! isset($key['key'])) {
                throw new InvalidArgumentException('Provided array doesn\'t include a "key" element');
            }

            $this->key = $key['key'];
        } elseif (is_string($key)) {
            $this->key = $key;
        } else {
            throw new InvalidArgumentException(sprintf(
                'Expected "array" or "string", provided "%s"',
                is_object($key) ? get_class($key) : gettype($key)
            ));
        }
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
