<?php

namespace STS\AwsEvents\Events;


class Config extends Event
{

    protected static $contains = 'configRuleName';

    public static function supports($event)
    {
        return $event->has(self::$contains);
    }
}
