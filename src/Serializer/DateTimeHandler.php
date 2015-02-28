<?php
namespace Wonnova\SDK\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;

/**
 * This fixes inconsistences in date fields returned from the API, so that all of them are cast into DateTime objects
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
        $wonnovaDateTimeDeserializationStrategyConfig = [
            'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
            'type' => 'WonnovaDateTime',
            'method' => 'deserializeWonnovaDateTime',
        ];
        $stringDateTimeDeserializationStrategyConfig = [
            'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
            'type' => 'StringDateTime',
            'method' => 'deserializeStringDateTime',
        ];

        return [
            static::getArrayWithFormat($wonnovaDateTimeDeserializationStrategyConfig),
            static::getArrayWithFormat($wonnovaDateTimeDeserializationStrategyConfig, 'array'),
            static::getArrayWithFormat($stringDateTimeDeserializationStrategyConfig),
            static::getArrayWithFormat($stringDateTimeDeserializationStrategyConfig, 'array')
        ];
    }

    private static function getArrayWithFormat($array, $format = 'json')
    {
        $array['format'] = $format;
        return $array;
    }

    public function deserializeWonnovaDateTime(
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

    public function deserializeStringDateTime(
        $visitor,
        $data,
        array $type,
        Context $context
    ) {
        return new \DateTime($data);
    }
}
