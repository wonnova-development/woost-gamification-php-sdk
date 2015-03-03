<?php
namespace Wonnova\SDK\Common;

/**
 * This provides a common method for all the objects to parse inconsistent serialized dates into DateTime objects
 * @author
 * @link
 */
trait WonnovaDateTimeParserTrait
{
    /**
     * Parses a date serialized as string, array or DateTime object itself
     *
     * @param array|string|\DateTime $data
     * @return \DateTime|null
     */
    protected function parseWonnovaDateTime($data)
    {
        if (is_array($data)) {
            $formatedDate = isset($data['date']) ? $data['date'] : 'now';
            $timezone = isset($data['timezone']) ? new \DateTimeZone($data['timezone']) : null;
            $dateTime = new \DateTime($formatedDate, $timezone);

            return $dateTime;
        } elseif (is_string($data)) {
            return new \DateTime($data);
        } elseif ($data instanceof \DateTime) {
            return $data;
        }

        return null;
    }
}
