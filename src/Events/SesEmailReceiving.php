<?php

namespace STS\Lambda\Events;

class SesEmailReceiving extends Event
{
    protected static $contains = 'Records.ses';

}
