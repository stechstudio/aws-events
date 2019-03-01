<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

use IteratorAggregate;
use STS\AwsEvents\Contracts\Arrayable;
use STS\AwsEvents\Contracts\Collectable;
use STS\AwsEvents\Contracts\Eventful;
use STS\AwsEvents\Contracts\Jsonable;
use Tightenco\Collect\Support\Collection;

/**
 * Class Event
 *
 * @property Collection $Records
 */
class Event implements Arrayable, Collectable, Jsonable, IteratorAggregate, \Countable, \JsonSerializable, Eventful
{
    /** @var string */
    protected static $contains = 'baseEvent';

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
        Sns::class,
    ];

    /** @var string */
    protected $rawEvent;

    /** @var Collection */
    protected $collection;

    public function __construct(string $rawEvent)
    {
        $this->rawEvent = $rawEvent;
        $this->collection = $this->recursiveCollect($this->decode());
    }

    /**
     * Ensure everything that can be a collection, is.
     *
     * @param array $array
     */
    protected function recursiveCollect(array $array): Collection
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->recursiveCollect($value);
                $array[$key] = $value;
            }
        }

        return new Collection($array);
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
     * Register a custom event type
     *
     * @throws \DomainException
     * @throws \ReflectionException
     */
    public static function register(string $eventClass): void
    {
        if (! (new \ReflectionClass($eventClass))->isSubclassOf(self::class)) {
            throw new \DomainException('Only subclasses of ' . self::class . ' may be registered');
        }

        self::$events[] = $eventClass;
    }

    /**
     * Generate an event from a file.
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
     * @return Event
     * @throws \JsonException
     */
    public static function fromString(string $event): self
    {
        return self::make($event);
    }

    public static function make(string $rawEvent): Event
    {
        $event = new Event($rawEvent);

        foreach (self::$events as $eventClassName) {
            if ($eventClassName::supports($event)) {
                return new $eventClassName($rawEvent);
            }
        }

        // We don't know what this is?
        // Hmm. Might want to log this so we can inspect later.
        // We simply return a bare event;
        return $event;
    }

    public static function supports(Event $event): bool
    {
        return $event->has(static::$contains);
    }

    /**
     * Determine if an item exists in the collection by key.
     */
    public function has(string $keyName): bool
    {
        $keys = explode('.', $keyName);

        $test = $this->collection;

        foreach ($keys as $key) {
            if ($test->has($key)) {
                if ($key === 'Records') {
                    try {
                        $test = $test->get('Records')->first();
                        continue;
                    } catch (\Throwable $t) {
                        return false;
                    }
                }
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
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->collection->get($name);
    }

    /**
     * Proxy a method call onto the collection.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
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
    public function toArray(): array
    {
        return $this->decode();
    }


    public function toCollection(int $options = 0): Collection
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
    public function toJson(int $options = 0): string
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
