<?php
namespace Wonnova\SDK\Auth;

/**
 * Interface CredentialsInterface
 * @author Wonnova
 * @link http://www.wonnova.com
 */
interface CredentialsInterface
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @return array
     */
    public function toArray();
}
