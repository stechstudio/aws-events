<?php declare(strict_types=1);

/**
 * User: bubba
 * Date: 2019-01-25
 * Time: 12:43
 */

namespace STS\AwsEvents\Contracts;

use Tightenco\Collect\Support\Collection;

interface Eventful
{
    /**
     * @inheritDoc
     */
    public function toArray();

    /**
     * @inheritDoc
     */
    public function toCollection(int $options = 0): Collection;

    /**
     * @inheritDoc
     */
    public function count();

    /**
     * @inheritDoc
     */
    public function toJson(int $options = 0): string;

    /**
     * @inheritDoc
     */
    public function jsonSerialize();
}
