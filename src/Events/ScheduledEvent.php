<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class ScheduledEvent extends Event
{
    /** @var string */
    protected static $contains = 'detail-type';
}
