<?php

namespace Touch\Core\Factories;

use Clockwork\Support\Vanilla\Clockwork;
use Psr\Container\ContainerInterface;
use Touch\Core\Clockwork\DataSource\TwigDataSource;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig
{
  public static function create(ContainerInterface $container)
  {
    $env = new Environment(
      new FilesystemLoader($container->get("root") . "/views")
    );
    $container->get(Clockwork::class)->addDataSource(self::getDataSource($env));
    return $env;
  }

  protected static function getDataSource(Environment $twig)
  {
    $dataSource = new TwigDataSource($twig);
    $dataSource->listenToEvents();
    return $dataSource;
  }
}
