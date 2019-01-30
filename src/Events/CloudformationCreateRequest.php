<?php

namespace STS\AwsEvents\Events;

class CloudformationCreateRequest extends Event
{
    protected static $contains = 'StackId';

    public static function supports($event)
    {
        return $event->has(self::$contains);
    }
}
