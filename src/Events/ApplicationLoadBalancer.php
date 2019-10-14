<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class ApplicationLoadBalancer
{
    /** @var string */
    protected static $contains = 'requestContext.elb';

    public static function supports(Event $event): bool
    {
        return $event->has(self::$contains);
    }
}