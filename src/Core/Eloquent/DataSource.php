<?php

namespace Touch\Core\Eloquent;

use Clockwork\DataSource\EloquentDataSource;

class DataSource extends EloquentDataSource
{
  // Register a listener collecting model events of specified type
  protected function listenToModelEvent($event)
  {
    $this->eventDispatcher->listen("eloquent.{$event}: *", function (
      $model,
      $data = null
    ) use ($event) {
      if (is_string($model) && is_array($data)) {
        // Laravel 5.4 wildcard event
        $model = reset($data);
      }

      $this->collectModelEvent($event, $model->getModel());
    });
  }
}
