<?php

namespace STS\AwsEvents\Events;

class SesEmailReceiving extends Event
{
    protected static $contains = 'Records.ses';

}
