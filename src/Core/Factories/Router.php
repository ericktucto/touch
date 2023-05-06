<?php

namespace Touch\Core\Factories;

use League\Route\Router as LeagueRouter;
use League\Route\Strategy\ApplicationStrategy;
use Psr\Container\ContainerInterface;

class Router
{
  public static function create(ContainerInterface $container)
  {
    $strategy = new ApplicationStrategy();
    $strategy->setContainer($container);

    $router = new LeagueRouter();
    $router->setStrategy($strategy);
    return $router;
  }
}
