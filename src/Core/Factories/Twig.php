<?php

namespace Touch\Core\Factories;

use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig
{
  public static function create(ContainerInterface $container)
  {
    return new Environment(
      new FilesystemLoader($container->get("root") . "/views")
    );
  }
}
