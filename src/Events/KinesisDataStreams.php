<?php

namespace STS\AwsEvents\Events;

class KinesisDataStreams extends Event
{
    protected static $contains = 'Records.eventSource';

    public static function supports($event)
    {
        return ($event->has(self::$contains) && $event->get('Records')->first()->get('eventSource') == 'aws:kinesis');

    }
}
