<?php

namespace STS\AwsEvents\Events;


class Lex extends Event
{
    protected static $contains = 'messageVersion';
}
