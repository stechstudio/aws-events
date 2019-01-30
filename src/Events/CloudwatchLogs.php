<?php

namespace STS\Lambda\Events;


class CloudwatchLogs extends Event
{
    protected static $contains = 'awslogs';

    public static function supports(Event $event)
    {
        return $event->has(self::$contains);
    }
}
