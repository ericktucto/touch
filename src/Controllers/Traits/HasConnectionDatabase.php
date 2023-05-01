<?php

namespace Touch\Controllers\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasConnectionDatabase
{
  protected function conn()
  {
    return Model::resolveConnection();
  }
}
