<?php

namespace STS\Lambda\Events;


class ScheduledEvent extends Event
{
    protected static $contains = 'detail-type';
}
