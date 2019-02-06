<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class Lex extends Event
{
    /** @var string */
    protected static $contains = 'messageVersion';
}
