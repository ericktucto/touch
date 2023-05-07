<?php

namespace Touch\Core;

use DI\Container;
use DI\ContainerBuilder;

class Kernel
{
  protected Container $container;
  public function __construct()
  {
    $builder = new ContainerBuilder();
    $builder->addDefinitions(__DIR__ . '/../config.php');
    $this->container = $builder->build();
  }

  public function getContainer(): Container
  {
    return $this->container;
  }
}
