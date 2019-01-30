<?php

namespace STS\AwsEvents\Events;


class ScheduledEvent extends Event
{
    protected static $contains = 'detail-type';
}
