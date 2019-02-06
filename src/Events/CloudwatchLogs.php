<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class CloudwatchLogs extends Event
{
    /** @var string */
    protected static $contains = 'awslogs';

    public static function supports(Event $event): bool
    {
        return $event->has(self::$contains);
    }
}
