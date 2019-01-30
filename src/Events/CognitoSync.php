<?php

namespace STS\Lambda\Events;


class CognitoSync extends Event
{

    protected static $contains = 'identityPoolId';

    public static function supports($event)
    {
        return $event->has(self::$contains);
    }
}
