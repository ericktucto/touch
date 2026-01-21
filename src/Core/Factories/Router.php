<?php

namespace Touch\Core\Factories;

use Psr\Container\ContainerInterface;
use Touch\Http\Router as HttpRouter;
use Touch\Http\TouchStrategy;

class Router
{
    public static function create(TouchStrategy $strategy, ContainerInterface $container)
    {
        $router = new HttpRouter();
        $router->setStrategy($strategy);
        return $router;
    }
}
