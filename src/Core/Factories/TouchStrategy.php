<?php

namespace Touch\Core\Factories;

use Psr\Container\ContainerInterface;
use Touch\Http\TouchStrategy as Strategy;

class TouchStrategy
{
    public static function create(ContainerInterface $container): Strategy
    {
        $strategy = new Strategy();
        $strategy->setContainer($container);
        return $strategy;
    }
}
