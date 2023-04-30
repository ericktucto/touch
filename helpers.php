<?php

use Touch\Application;

function app(?string $resource = null)
{
    $app = Application::getContainer();
    return is_null($resource) ? $app : $app->get($resource);
}

function controller(string $controller, $method)
{
    return "{$controller}@{$method}";
}
