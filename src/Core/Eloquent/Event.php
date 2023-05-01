<?php

namespace Touch\Core\Eloquent;

use Illuminate\Database\Eloquent\Model;
use League\Event\HasEventName;

class Event implements HasEventName
{
  public function __construct(protected string $name, protected ?Model $model = null)
  {
  }
  public function eventName(): string
  {
    return $this->name;
  }

  public function getModel(): Model
  {
    return $this->model;
  }

  public function addGlobalScope($scope)
  {
    $this->model->addGlobalScope($scope);
  }
}
