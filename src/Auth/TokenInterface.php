<?php
namespace Wonnova\SDK\Auth;

/**
 * Interface TokenInterface
 * @author Wonnova
 * @link http://www.wonnova.com
 */
interface TokenInterface
{
    const HEADER_KEY = 'Auth-Token';

    /**
     * Returns auth token
     *
     * @return string
     */
    public function getAccessToken();

    /**
     * @return array
     */
    public function toArray();
}
