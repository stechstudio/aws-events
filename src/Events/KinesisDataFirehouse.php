<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class KinesisDataFirehouse extends Event
{
    /** @var string */
    protected static $contains = 'invocationId';
}
