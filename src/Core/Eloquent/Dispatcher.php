<?php

namespace Touch\Core\Eloquent;

use Illuminate\Contracts\Events\Dispatcher as IDispatcher;
use League\Event\EventDispatcher;

class Dispatcher implements IDispatcher
{
    protected EventDispatcher $dispatcher;

    public function __construct()
    {
      $this->dispatcher = new EventDispatcher();
    }

    public function dispatch($event, $payload = [], $halt = false)
    {
      if ($this->hasListeners($event)) {
        $pattern = [];
        preg_match("/(eloquent\.\w*:)\ (.*)/", $event, $pattern);
        $this->dispatcher->dispatch(new Event("{$pattern[1]} *", $payload));
      }
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
        foreach (/*$this->listeners*/[] as $key => $value) {
            if (str_ends_with($key, '_pushed')) {
                $this->forget($key);
            }
        }
    }

    public function hasListeners($eventName): bool
    {
      return str_contains($eventName, "eloquent");
    }

    public function listen($events, $listener = null): void
    {
      $events = is_string($events) ? [$events] : $events;
      if (is_array($events)) {
        foreach ($events as $event) {
          $this->dispatcher->subscribeTo($event, $listener);
        }
      }
    }

    public function push($event, $payload = []): void
    {
        $this->listen($event."_pushed", fn() => $this->dispatch($event, $payload));
    }

    public function subscribe($subscriber): void {

    }
}
