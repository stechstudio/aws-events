<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class Config extends Event
{
    /** @var string */
    protected static $contains = 'configRuleName';

    public static function supports(Event $event): bool
    {
        return $event->has(self::$contains);
    }
}
