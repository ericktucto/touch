<?php

namespace Touch\Core\Factories;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Container\ContainerInterface;
use Touch\Http\Request as HttpRequest;

class Request
{
  public static function create(ContainerInterface $container)
  {
    return new HttpRequest($container->get(ServerRequest::class));
  }
}
