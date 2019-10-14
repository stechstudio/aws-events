<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class ApiGatewayProxyRequest extends Event
{
    /** @var string */
    protected static $contains = 'requestContext.apiId';

    public static function supports(Event $event): bool
    {
        return $event->has(self::$contains);
    }
}
