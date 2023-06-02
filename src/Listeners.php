<?php

namespace DPHPDriver;

use Discord\Discord;
use Discord\WebSockets\Event;

/**
 * A Events Listener closures Manager
 */
class Listeners
{
    /**
     * @var array $listeners An array that holds the assigned event listener closures.
     */
    private array $listeners = [];
    
    /**
     * Execute the assigned event listener closures.
     *
     * @param Discord $discord
     */
    public function execEventListeners(Discord $discord): void
    {
        foreach ($this->listeners as $listener) {
            $eventType = $listener["eventType"];
            $callback = $listener["callback"];
            $discord->on($eventType, $callback);
        }
    }

    /**
     * Assign an event listener closure.
     *
     * @param Event|string $eventType
     * @param callable $callback
     * @return $this
     */
    public function addEventListener($eventType, callable $callback): self
    {
        $listener = [
            "eventType" => $eventType,
            "callback" => $callback
        ];
        $this->listeners[] = $listener;
        return $this;
    }

    /**
     * Truncate assigned event closures.
     */
    public function truncateEventClosures(): void
    {
        $this->listeners = [];
    }
}
