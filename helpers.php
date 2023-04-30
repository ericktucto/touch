<?php

use GuzzleHttp\Psr7\ServerRequest;
use Touch\Application;
use Touch\Http\Request;

function app(?string $resource = null)
{
    $app = Application::getContainer();
    return is_null($resource) ? $app : $app->get($resource);
}

function controller(string $controller, $method)
{
  return "{$controller}:{$method}";
}

function request(ServerRequest $request)
{
  return new Request($request);
}
