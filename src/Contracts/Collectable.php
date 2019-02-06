<?php declare(strict_types=1);

namespace STS\AwsEvents\Contracts;

use Tightenco\Collect\Support\Collection;

interface Collectable
{
    /**
     * Get the instance as a collection
     */
    public function toCollection(int $options = 0): Collection;
}
