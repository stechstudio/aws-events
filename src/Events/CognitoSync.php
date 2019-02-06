<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class CognitoSync extends Event
{
    /** @var string */
    protected static $contains = 'identityPoolId';

    public static function supports(Event $event): bool
    {
        return $event->has(self::$contains);
    }
}
