<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class IotButton extends Event
{
    /** @var string */
    protected static $contains = 'clickType';
}
