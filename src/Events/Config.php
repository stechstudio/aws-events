<?php

namespace STS\Lambda\Events;


class Config extends Event
{

    protected static $contains = 'configRuleName';

    public static function supports($event)
    {
        return $event->has(self::$contains);
    }
}
