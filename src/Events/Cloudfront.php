<?php

namespace STS\Lambda\Events;

class Cloudfront extends Event
{
    protected static $contains = 'Records.cf';
}
