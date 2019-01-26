<?php

namespace STS\Lambda\Foundation;

use IteratorAggregate;
use STS\Lambda\Contracts\Arrayable;
use STS\Lambda\Contracts\Collectable;
use STS\Lambda\Contracts\Eventful;
use STS\Lambda\Contracts\Jsonable;

class Event implements Arrayable, Collectable, Jsonable, IteratorAggregate, \Countable, \JsonSerializable, Eventful
{
    /**
     * @var string
     */
    protected $rawEvent;
    /**
     * @var \Tightenco\Collect\Support\Collection
     */
    protected $collection;

    /**
     * Event constructor.
     * @param string $rawEvent
     * @throws \JsonException
     */
    public function __construct(string $rawEvent)
    {
        $this->rawEvent = $rawEvent;
        $this->collection = $this->recursiveCollect($this->decode());
    }

    /**
     * Ensure everything that can be a collection, is.
     * @param array $array
     * @return \Tightenco\Collect\Support\Collection
     */
    protected function recursiveCollect(array $array): \Tightenco\Collect\Support\Collection
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->recursiveCollect($value);
                $array[$key] = $value;
            }
        }

        return collect($array);
    }

    /**
     * Decodes the raw event into an associative array
     * @throws \JsonException
     * @return array
     */
    protected function decode(): array
    {

        return json_decode($this->rawEvent, $assoc = true, $depth = 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Generate an event from a file.
     * @param string $path
     * @return Event
     * @throws \JsonException
     */
    public static function fromFile(string $path): self
    {
        return self::fromString(file_get_contents($path));
    }

    /**
     * Generate an event from a string.
     * @param string $event
     * @return Event
     * @throws \JsonException
     */
    public static function fromString(string $event): self
    {
        return new self($event);
    }

    /**
     * Get an item from the event by key.
     *
     * @param  mixed $key
     * @param  mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->collection->get($key, $default);
    }

    /**
     * Proxy an attribute request in a collection->get($key);
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->collection->get($name);
    }

    /**
     * Proxy a method call onto the collection.
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->collection, $method)) {
            if (is_callable($parameters[0])) {
                return $this->collection->{$method}($parameters[0]);
            }
            return $this->collection->{$method}($parameters);
        }
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return $this->decode();
    }

    /**
     * @inheritDoc
     */
    public function toCollection($options = 0)
    {
        return $this->collection;
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return $this->collection->count();
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0)
    {
        return $this->rawEvent;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->rawEvent;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->collection->jsonSerialize();
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return $this->collection->getIterator();
    }
}
