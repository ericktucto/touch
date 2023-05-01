<?php

namespace Touch\Core\Factories;

use IPub\SlimRouter\Routing\Router as SlimRouter;

class Router
{
  public static function create()
  {
    return new SlimRouter();
  }
}
