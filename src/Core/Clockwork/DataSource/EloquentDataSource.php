<?php

namespace Touch\Core\Clockwork\DataSource;

use Clockwork\DataSource\EloquentDataSource as DataSource;

class EloquentDataSource extends DataSource
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
