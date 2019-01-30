<?php
/**
 * Created by PhpStorm.
 * User: bubba
 * Date: 2019-01-25
 * Time: 12:43
 */

namespace STS\AwsEvents\Contracts;

interface Eventful
{
    /**
     * @inheritDoc
     */
    public function toArray();

    /**
     * @inheritDoc
     */
    public function toCollection($options = 0);

    /**
     * @inheritDoc
     */
    public function count();

    /**
     * @inheritDoc
     */
    public function toJson($options = 0);

    /**
     * @inheritDoc
     */
    public function jsonSerialize();
}
