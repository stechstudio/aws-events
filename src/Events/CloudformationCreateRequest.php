<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class CloudformationCreateRequest extends Event
{
    /** @var string */
    protected static $contains = 'StackId';

    public static function supports(Event $event): bool
    {
        return $event->has(self::$contains);
    }
}
