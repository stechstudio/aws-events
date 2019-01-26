<?php

namespace STS\Lambda\Contracts;

interface Collectable
{
    /**
     * Get the instance as a collection
     *
     * @param  int $options
     * @return string
     */
    public function toCollection($options = 0);
}
