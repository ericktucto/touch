<?php

namespace Touch\Core\Clockwork\Laravel;

use Illuminate\Database\Events\QueryExecuted;
use League\Event\HasEventName;

class QueryEvent implements HasEventName
{
  public function __construct(protected QueryExecuted $query)
  {
  }
  public function eventName(): string
  {
    return get_class($this->query);
  }
  public function __get($name)
  {
    return $this->query->{$name};
  }
}
