<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class S3Delete extends Event
{
    /** @var string */
    protected static $contains = 'Records.eventName';

    public static function supports(Event $event): bool
    {
        return $event->has(self::$contains) && $event->get('Records')->first()->get('eventName') === 'ObjectRemoved:Delete';
    }
}
