<?php

namespace STS\AwsEvents\Events;


class KinesisDataFirehouse extends Event
{
    protected static $contains = 'invocationId';
}
