<?php

use Bramus\Router\Router;
use GuzzleHttp\Psr7\ServerRequest;
use Touch\Http\Contracts\Request;
use Touch\Http\Request as HttpRequest;
use Twig\{Environment, Loader\FilesystemLoader};
use Psr\Container\ContainerInterface;

return [
  Router::class => fn() => new Router(),
  Request::class => fn(ContainerInterface $c) => new HttpRequest(
    $c->get(ServerRequest::class)
  ),
  ServerRequest::class => fn() => ServerRequest::fromGlobals(),
  "twig" => fn() => new Environment(new FilesystemLoader(__DIR__ . "/views")),
];
