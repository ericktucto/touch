<?php

namespace Touch\Core\Factories;

use League\Route\Strategy\ApplicationStrategy;
use Psr\Container\ContainerInterface;
use Touch\Http\Router as HttpRouter;

class Router
{
  public static function create(ContainerInterface $container)
  {
    $strategy = new ApplicationStrategy();
    $strategy->setContainer($container);

    $router = new HttpRouter();
    $router->setStrategy($strategy);
    return $router;
  }
}
