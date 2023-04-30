<?php

use IPub\SlimRouter\Routing\Router;
use GuzzleHttp\Psr7\ServerRequest;
use Touch\Http\Contracts\Request;
use Touch\Http\Request as HttpRequest;
use Twig\{Environment, Loader\FilesystemLoader};
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

return [
  Router::class => fn() => new Router(),
  Request::class => fn(ContainerInterface $c) => new HttpRequest(
    $c->get(ServerRequest::class)
  ),
  ServerRequestInterface::class => fn() => ServerRequest::fromGlobals(),
  "twig" => fn() => new Environment(new FilesystemLoader(__DIR__ . "/views")),
];
