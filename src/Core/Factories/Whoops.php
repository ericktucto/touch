<?php

namespace Touch\Core\Factories;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Whoops
{
  public static function create()
  {
    $whoops = new Run();
    $whoops->pushHandler(new PrettyPageHandler);
    $whoops->register();
    return $whoops;
  }
}
