<?php

namespace Touch\Http;

use League\Route\Router as LeagueRouter;

class Router extends LeagueRouter
{
  public function getRouteData()
  {
    return $this->routeCollector->getData();
  }
}

