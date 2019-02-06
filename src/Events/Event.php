<?php

namespace STS\AwsEvents\Events;

use IteratorAggregate;
use STS\AwsEvents\Contracts\Arrayable;
use STS\AwsEvents\Contracts\Collectable;
use STS\AwsEvents\Contracts\Eventful;
use STS\AwsEvents\Contracts\Jsonable;
use Symfony\Component\Process\Process;

class Event implements Arrayable, Collectable, Jsonable, IteratorAggregate, \Countable, \JsonSerializable, Eventful
{
    /**
     * Default event types.
     * Order of the array matters.
     *
     * @var array
     */
    protected static $events = [
        CloudwatchLogs::class,
        CognitoSync::class,
        Lex::class,
        ApiGatewayProxyRequest::class,
        CloudformationCreateRequest::class,
        Config::class,
        IotButton::class,
        KinesisDataFirehouse::class,
        ScheduledEvent::class,
        Cloudfront::class,
        S3Delete::class,
        S3Put::class,
        DynamodbUpdate::class,
        KinesisDataStreams::class,
        SesEmailReceiving::class,
        Sqs::class,
        Sns::class
    ];

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
     *
     * @param string $rawEvent
     */
    public function __construct(string $rawEvent)
    {
        $this->rawEvent = $rawEvent;
        $this->collection = $this->recursiveCollect($this->decode());
    }

    /**
     * Register a custom event type
     *
     * @param string $eventClass
     *
     * @throws \DomainException
     * @throws \ReflectionException
     */
    public static function register(string $eventClass)
    {
        if (!(new \ReflectionClass($eventClass))->isSubclassOf(Event::class)) {
            throw new \DomainException("Only subclasses of " . Event::class . " may be registered");
        }

        self::$events[] = $eventClass;
    }

    /**
     * Ensure everything that can be a collection, is.
     *
     * @param array $array
     *
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
     *
     * @return array
     */
    protected function decode(): array
    {
        return json_decode($this->rawEvent, $assoc = true, $depth = 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Generate an event from a file.
     *
     * @param string $path
     *
     * @return Event
     * @throws \JsonException
     */
    public static function fromFile(string $path): self
    {
        return self::fromString(file_get_contents($path));
    }

    /**
     * Generate an event from a string.
     *
     * @param string $event
     *
     * @return Event
     * @throws \JsonException
     */
    public static function fromString(string $event): self
    {
        return self::make($event);
    }

    /**
     * @param $rawEvent
     *
     * @return Event
     * @throws \JsonException
     */
    public static function make($rawEvent): Event
    {
        $event = new Event($rawEvent);

        foreach (self::$events as $eventClassName) {
            if ($eventClassName::supports($event)) {
                $result = new $eventClassName($rawEvent);

                return $result;
            }
        }

        // We don't know what this is?
        // Hmm. Might want to log this so we can inspect later.
        // We simply return a bare event;
        return $event;
    }

    public static function supports(Event $event)
    {
        return $event->has(static::$contains);
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param string $keyName
     *
     * @return bool
     */
    public function has(string $keyName): bool
    {
        $keys = explode('.', $keyName);

        $test = $this->collection;

        foreach ($keys as $key) {
            if ($key == 'Records') {
                $test = $test->get('Records')->first();
                continue;
            }
            if ($test->has($key)) {
                $test = $test->get($key);
                continue;
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * Get an item from the event by key.
     *
     * @param mixed $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->collection->get($key, $default);
    }

    /**
     * Proxy an attribute request in a collection->get($key);
     *
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->collection->get($name);
    }

    /**
     * Proxy a method call onto the collection.
     *
     * @param string $method
     * @param array  $parameters
     *
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
        return null;
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
