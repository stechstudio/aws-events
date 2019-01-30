<?php

namespace STS\Lambda\Events;


class S3Put extends Event
{

    protected static $contains = 'Records.eventName';

    public static function supports($event)
    {
        return ($event->has(self::$contains) && $event->get('Records')->first()->get('eventName') == 'ObjectCreated:Put');
    }
}
