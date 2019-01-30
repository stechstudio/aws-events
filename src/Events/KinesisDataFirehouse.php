<?php

namespace STS\Lambda\Events;


class KinesisDataFirehouse extends Event
{
    protected static $contains = 'invocationId';
}
