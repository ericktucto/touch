<?php

namespace Touch\Core;

use DI\Container;
use DI\ContainerBuilder;
use Touch\Core\Factories\Config as ConfigFactory;

use function DI\factory;

class Kernel
{
  protected Container $container;
  protected string $projectPath;
  public function __construct(?string $projectPath = null)
  {
    $filePath = debug_backtrace()[0]["file"];
    $this->projectPath = $projectPath ?? dirname(dirname($filePath));

    $builder = new ContainerBuilder();
    $builder->addDefinitions(__DIR__ . "/../config.php");
    $builder->addDefinitions([
      "config" => factory([ConfigFactory::class, "create"])->parameter(
        "path",
        $this->projectPath . "/config.yml"
      ),
      "path" => fn () => $this->projectPath,
    ]);
    $this->container = $builder->build();
  }

  public function getContainer(): Container
  {
    return $this->container;
  }
}
