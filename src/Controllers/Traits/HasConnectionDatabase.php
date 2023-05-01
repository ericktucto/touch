<?php

namespace Touch\Controllers\Traits;

use Illuminate\Database\ConnectionResolverInterface;

trait HasConnectionDatabase
{
  protected function conn()
  {
    return app(ConnectionResolverInterface::class);
  }
}
