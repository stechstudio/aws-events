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
        if ($this->containsEvent()) {
            return $this->containedEvent;
        }
        return null;
    }

    /**
     * @param String $rawEvent
     * @return bool
     */
    public function containsEvent(String $rawEvent): bool
    {
        if (!is_null($this->containedEvent)) {
            return true;
        }
        $event = null;
        try {
            $event = Event::make($rawEvent);
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
