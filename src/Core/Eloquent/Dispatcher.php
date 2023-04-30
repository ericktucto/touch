<?php

namespace Touch\Core\Eloquent;

use Illuminate\Contracts\Events\Dispatcher as EloquentDispatcher;

class Dispatcher implements EloquentDispatcher
{
    protected array $listeners = [];

    public function dispatch($event, $payload = [], $halt = false)
    {
        clock("dispatch: init", $event, "dispatch: end");
    }

    public function until($event, $payload = [])
    {
        return $this->dispatch($event, $payload, true);
    }

    public function flush($event): void
    {
        $this->dispatch($event."_pushed");
    }

    public function forget($event): void {
        
    }

    public function forgetPushed(): void
    {
        foreach ($this->listeners as $key => $value) {
            if (str_ends_with($key, '_pushed')) {
                $this->forget($key);
            }
        }
    }

    public function hasListeners($eventName): bool
    {
        clock("hasListeners: init", $eventName, "hasListeners: end");
        return isset($this->listeners[$eventName]);
    }

    public function listen($events, $listener = null): void {
        clock("listen: init", $events, "listen: end");
    }

    public function push($event, $payload = []): void
    {
        $this->listen($event."_pushed", fn() => $this->dispatch($event, $payload));
    }

    public function subscribe($subscriber): void {
        
    }
}
