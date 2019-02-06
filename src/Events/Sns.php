<?php declare(strict_types=1);

namespace STS\AwsEvents\Events;

/**
 * Class Sns
 *
 * @mixin Event
 */
class Sns extends Event
{
    /** @var string */
    protected static $contains = 'Records.Sns';
    /** @var Event */
    protected $containedEvent;

    public function getContainedEvent(): ?Event
    {
        return $this->containedEvent;
    }

    public function containsEvent(): bool
    {
        if (! empty($this->containedEvent)) {
            return true;
        }

        try {
            $event = Event::make($this->Records->first()->Sns->Message);
        } catch (\JsonException $e) {
            return false;
        }

        if (is_subclass_of($event, Event::class)) {
            $this->containedEvent = $event;
            return true;
        }

        return false;
    }
}
