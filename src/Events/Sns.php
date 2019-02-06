<?php

namespace STS\AwsEvents\Events;

class Sns extends Event
{
    protected static $contains = 'Records.Sns';
    protected $containedEvent = null;

    /**
     * @return Event|null
     */
    public function getContainedEvent(): ?Event
    {
        return $this->containedEvent;
    }

    /**
     * @return bool
     */
    public function containsEvent(): bool
    {
        if (!is_null($this->containedEvent)) {
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
