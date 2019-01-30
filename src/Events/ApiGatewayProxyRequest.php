<?php

namespace STS\AwsEvents\Events;


class ApiGatewayProxyRequest extends Event
{
    protected static $contains = 'requestContext';

    public static function supports($event)
    {
        return $event->has(self::$contains);
    }
}
