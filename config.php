<?php

use Clockwork\Support\Vanilla\Clockwork;
use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Database\ConnectionResolverInterface;
use IPub\SlimRouter\Routing\Route;
use Touch\Http\Contracts\Request;
use Psr\Http\Message\ServerRequestInterface;
use Touch\Core\Factories\{
  Clockwork as ClockworkFactory,
  EloquentConnection as EloquentConnectionFactory,
  Request as RequestFactory,
  Router as RouterFactory,
  Twig as TwigFactory
};

use function DI\factory;

return [
  Route::class => factory([RouterFactory::class, "create"]),
  Request::class => factory([RequestFactory::class, "create"]),
  ConnectionResolverInterface::class => factory([
    EloquentConnectionFactory::class,
    "create",
  ]),
  Clockwork::class => factory([ClockworkFactory::class, "create"]),
  ServerRequestInterface::class => fn() => ServerRequest::fromGlobals(),
  "twig" => factory([TwigFactory::class, "create"]),
  "root" => fn() => __DIR__,
];
