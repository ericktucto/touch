<?php

namespace Touch\Core\Factories;

use Clockwork\Support\Vanilla\Clockwork;
use Noodlehaus\Config;
use Psr\Container\ContainerInterface;
use Touch\Core\Clockwork\DataSource\TwigDataSource;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig
{
  public static function create(ContainerInterface $container)
  {
    $path = static::getPathViews($container, $container->get("config"));
    $env = new Environment(new FilesystemLoader($path));
    $container->get(Clockwork::class)->addDataSource(self::getDataSource($env));
    return $env;
  }

  protected static function getPathViews(
    ContainerInterface $container,
    Config $config
  ): string {
    $path = match (strpos($config->get("views"), "/")) {
      0 => $config->get("views"),
      default => "{$container->get('path')}/{$config->get("views")}",
    };
    // delete "/" on end string
    return preg_replace("/\/$/", "", $path);
  }

  protected static function getDataSource(Environment $twig)
  {
    $dataSource = new TwigDataSource($twig);
    $dataSource->listenToEvents();
    return $dataSource;
  }
}
