<?php
namespace Wonnova\SDK\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;

/**
 * Class DateTimeHandler
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class DateTimeHandler implements SubscribingHandlerInterface
{
    /**
     * Return format:
     *
     *      array(
     *          array(
     *              'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
     *              'format' => 'json',
     *              'type' => 'DateTime',
     *              'method' => 'serializeDateTimeToJson',
     *          ),
     *      )
     *
     * The direction and method keys can be omitted.
     *
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return [[
            'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
            'format' => 'json',
            'type' => 'WonnovaDateTime',
            'method' => 'serializeDateTimeToJson',
        ]];
    }

    public function serializeDateTimeToJson(
        $visitor,
        $data,
        array $type,
        Context $context
    ) {
        if (is_array($data)) {
            $formatedDate = isset($data['date']) ? $data['date'] : 'now';
            $timezone = isset($data['timezone']) ? new \DateTimeZone($data['timezone']) : null;
            $dateTime = new \DateTime($formatedDate, $timezone);

            return $dateTime;
        } elseif (is_string($data)) {
            return new \DateTime($data);
        }

        return null;
    }
}
