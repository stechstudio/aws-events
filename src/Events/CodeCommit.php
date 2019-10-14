<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

class CodeCommit extends Event
{
    /** @var string */
    protected static $contains = 'Records.codecommit';
}
