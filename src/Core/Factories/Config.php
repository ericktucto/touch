<?php

namespace Touch\Core\Factories;

use Noodlehaus\Config as NoodlehausConfig;
use Psr\Container\ContainerInterface;
use Touch\Exceptions\NotExistsConfig;

class Config
{
  public static function create(string $path, ContainerInterface $container)
  {
    static::checkExistsConfig($path, $container);
    return NoodlehausConfig::load($path);
  }

  public static function checkExistsConfig(string $path, ContainerInterface $container)
  {
    if (file_exists($path)) {
      return $path;
    }
    $container->get("whoops");
    throw new NotExistsConfig("Error Processing Request", 1);
  }
}
