<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class Alexa extends Event
{
    /** @var string */
    protected static $contains = 'payload.applianceId';

    public static function supports(Event $event): bool
    {
        return $event->has(self::$contains);
    }
}
