<?php

namespace STS\Lambda\Events;


class S3Delete extends Event
{

    protected static $contains = 'Records.eventName';

    public static function supports($event)
    {
        return ($event->has(self::$contains) && $event->get('Records')->first()->get('eventName') == 'ObjectRemoved:Delete');
    }
}
