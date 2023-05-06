<?php

use Clockwork\Support\Vanilla\Clockwork;
use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Database\ConnectionResolverInterface;
use League\Route\Router;
use Touch\Http\Contracts\Request;
use Psr\Http\Message\ServerRequestInterface;
use Touch\Core\Factories\{
  Clockwork as ClockworkFactory,
  EloquentConnection as EloquentConnectionFactory,
  EngineTemplate as EngineTemplateFactory,
  Request as RequestFactory,
  Router as RouterFactory,
  Twig as TwigFactory
};
use Touch\Http\EngineTemplate;

use function DI\factory;

return [
  Router::class => factory([RouterFactory::class, "create"]),
  Request::class => factory([RequestFactory::class, "create"]),
  ConnectionResolverInterface::class => factory([
    EloquentConnectionFactory::class,
    "create",
  ]),
  Clockwork::class => factory([ClockworkFactory::class, "create"]),
  ServerRequestInterface::class => fn() => ServerRequest::fromGlobals(),
  EngineTemplate::class => factory([EngineTemplateFactory::class, "create"]),
  "twig" => factory([TwigFactory::class, "create"]),
  "root" => fn() => __DIR__,
];
