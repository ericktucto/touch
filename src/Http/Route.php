<?php

namespace Touch\Http;

use League\Route\RouteGroup;

abstract class Route
{
    abstract protected function routes(RouteGroup $group);
    public function __invoke(RouteGroup $group)
    {
        $this->routes($group);
    }
}
