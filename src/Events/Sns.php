<?php

namespace STS\AwsEvents\Events;

class Sns extends Event
{

    protected static $contains = 'Records.Sns';

}
