<?php

use Clockwork\Support\Vanilla\Clockwork;
use Illuminate\Database\ConnectionResolverInterface;
use Psr\Http\Message\ServerRequestInterface;
use Touch\Core\Factories\{
  Clockwork as ClockworkFactory,
  EloquentConnection as EloquentConnectionFactory,
  EngineTemplate as EngineTemplateFactory,
  Router as RouterFactory,
  ServerRequest as ServerRequestFactory,
  Twig as TwigFactory,
  Whoops as WhoopsFactory,
};
use Touch\Http\EngineTemplate;
use Touch\Http\Router;

use function DI\factory;

return [
  Router::class => factory([RouterFactory::class, "create"]),
  ConnectionResolverInterface::class => factory([
    EloquentConnectionFactory::class,
    "create",
  ]),
  Clockwork::class => factory([ClockworkFactory::class, "create"]),
  ServerRequestInterface::class => factory([ServerRequestFactory::class, "create"]),
  EngineTemplate::class => factory([EngineTemplateFactory::class, "create"]),
  "twig" => factory([TwigFactory::class, "create"]),
  "root" => fn() => __DIR__,
  "whoops" => factory([WhoopsFactory::class, "create"]),
];
