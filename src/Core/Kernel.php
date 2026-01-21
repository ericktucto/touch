<?php

namespace Touch\Core;

use DI\Container;
use DI\ContainerBuilder;
use Exception;
use Touch\Core\Factories\Config as ConfigFactory;

use function DI\factory;

class Kernel
{
    protected ?Container $container = null;
    protected string $projectPath;
    protected ContainerBuilder $builder;

    public function __construct(?string $projectPath = null)
    {
        $filePath = debug_backtrace()[0]["file"];
        $this->projectPath = $projectPath ?? dirname(dirname($filePath));

        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__ . "/../config.php");
        $builder->addDefinitions([
            "config" => factory([ConfigFactory::class, "create"])->parameter(
                "path",
                $this->projectPath . "/config.yml",
            ),
            "path" => fn() => $this->projectPath,
        ]);
        $this->builder = $builder;
    }

    public function build(): void
    {
        $this->container = $this->builder->build();
    }

    public function getContainer(): Container
    {
        if (!$this->container) {
            throw new Exception("Done build kernel first");
        }
        return $this->container;
    }
}
