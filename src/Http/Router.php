<?php

namespace Touch\Http;

use League\Route\Middleware\MiddlewareAwareInterface;
use League\Route\RouteGroup;
use League\Route\Router as LeagueRouter;
use Override;
use Psr\Http\Server\MiddlewareInterface;

class Router extends LeagueRouter
{
    public function getRouteData(): array
    {
        return $this->routeCollector->getData();
    }

    #[Override]
    public function group(
        string $prefix,
        callable $group,
        MiddlewareAwareInterface|array|string|null $middleware = null,
    ): RouteGroup {
        $group = new RouteGroup($prefix, $group, $this);
        if (is_string($middleware)) {
            $group->middleware($this->resolveMiddleware($middleware));
        }
        $this->groups[] = $group;
        return $group;
    }

    #[Override]
    public function middleware(MiddlewareInterface|string $middleware): MiddlewareAwareInterface
    {
        if (is_string($middleware)) {
            $middleware = $this->resolveMiddleware($middleware);
        }
        $this->middleware[] = $middleware;
        return $this;
    }
}
