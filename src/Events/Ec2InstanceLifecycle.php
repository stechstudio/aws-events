<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class Ec2InstanceLifecycle
{
    /** @var string */
    protected static $contains = 'source';

    public static function supports(Event $event): bool
    {
        return $event->has(self::$contains)
            && $event->get('Records')->first()->get('source') === 'aws.ec2';
    }
}