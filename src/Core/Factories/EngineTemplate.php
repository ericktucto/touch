<?php

namespace Touch\Core\Factories;

use Psr\Container\ContainerInterface;
use Touch\Http\EngineTemplate as HttpEngineTemplate;

class EngineTemplate
{
    public static function create(ContainerInterface $container)
    {
        return new HttpEngineTemplate($container->get("twig"));
    }
}
