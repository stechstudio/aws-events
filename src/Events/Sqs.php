<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class Sqs extends Event
{
    /** @var string */
    protected static $contains = 'Records.eventSource';

    public static function supports(Event $event): bool
    {
        return $event->has(self::$contains) && $event->get('Records')->first()->get('eventSource') === 'aws:sqs';
    }
}
