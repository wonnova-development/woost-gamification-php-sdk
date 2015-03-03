<?php
namespace Wonnova\SDK\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use Wonnova\SDK\Common\WonnovaDateTimeParserTrait;

/**
 * This fixes inconsistences in date fields returned from the API, so that all of them are cast into DateTime objects
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class DateTimeHandler implements SubscribingHandlerInterface
{
    use WonnovaDateTimeParserTrait;

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

        return [
            static::getArrayWithFormat($wonnovaDateTimeDeserializationStrategyConfig),
            static::getArrayWithFormat($wonnovaDateTimeDeserializationStrategyConfig, 'array'),
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
        return $this->parseWonnovaDateTime($data);
    }
}
