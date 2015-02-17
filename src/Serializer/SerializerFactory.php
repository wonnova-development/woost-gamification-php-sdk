<?php
namespace Wonnova\SDK\Serializer;

use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializerBuilder;

/**
 * Class SerializerFactory
 * @author Wonnova
 * @link http://www.wonnova.com
 */
class SerializerFactory
{
    /**
     * @return \JMS\Serializer\Serializer
     */
    public static function create()
    {
        // This makes annotations autoloading work with existing annotation classes
        AnnotationRegistry::registerLoader('class_exists');

        return SerializerBuilder::create()
            ->setPropertyNamingStrategy(new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy()))
            ->configureHandlers(function (HandlerRegistry $registry) {
                $registry->registerSubscribingHandler(new DateTimeHandler());
            })
            ->build();
    }
}
