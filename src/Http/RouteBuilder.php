<?php

namespace Touch\Http;

use DI\Container;
use League\Route\Route;
use Touch\Http\Interfaces\RouteBuilderInterface;

/**
 * Use to implement routes with League\Route
 */
class RouteBuilder implements RouteBuilderInterface
{
    protected Container $container;
    protected Route $route;

    public function get(string $path, callable $callback): RouteBuilder
    {
        $router = new Route();
    }
}
