<?php

use GuzzleHttp\Psr7\ServerRequest;
use IPub\SlimRouter\Routing\Route;
use Touch\Http\Contracts\Request;
use Psr\Http\Message\ServerRequestInterface;
use Touch\Core\Factories\{
  Request as RequestFactory,
  Router as RouterFactory,
  Twig as TwigFactory
};

use function DI\factory;

return [
  Route::class => factory([RouterFactory::class, "create"]),
  Request::class => factory([RequestFactory::class, "create"]),
  ServerRequestInterface::class => fn() => ServerRequest::fromGlobals(),
  "twig" => factory([TwigFactory::class, "create"]),
  "root" => fn() => __DIR__,
];
