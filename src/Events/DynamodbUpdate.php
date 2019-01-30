<?php

namespace STS\Lambda\Events;

class DynamodbUpdate extends Event
{
    protected static $contains = 'Records.eventSource';

    public static function supports($event)
    {
        return ($event->has(self::$contains) && $event->get('Records')->first()->get('eventSource') == 'aws:dynamodb');
    }
}
