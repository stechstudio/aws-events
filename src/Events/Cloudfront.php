<?php

namespace STS\AwsEvents\Events;

class Cloudfront extends Event
{
    protected static $contains = 'Records.cf';
}
