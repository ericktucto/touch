<?php

namespace Touch\Core\Factories;

use Psr\Container\ContainerInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Whoops
{
    public static function create(ContainerInterface $container): ?Run
    {
        $env = $container->get('config')->get('env');
        if ($env !== 'local') {
            return null;
        }
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();
        return $whoops;
    }
}
