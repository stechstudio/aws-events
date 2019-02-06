<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class SesEmailReceiving extends Event
{
    /** @var string */
    protected static $contains = 'Records.ses';
}
