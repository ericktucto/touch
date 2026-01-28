<?php

namespace Touch\Core\Factories;

use Noodlehaus\Config as NoodlehausConfig;
use Psr\Container\ContainerInterface;
use Touch\Exceptions\BadStructBasicConfig;
use Touch\Exceptions\NotExistsConfig;

class Config
{
    protected static array $allowed_environment = ["local", "production"];

    public static function create(
        string $path,
        ContainerInterface $container,
    ): NoodlehausConfig {
        static::checkExistsConfig($path, $container);
        $config = NoodlehausConfig::load($path);
        $project_dir = dirname($path);
        $config->set('app.project_dir', $project_dir);
        static::checkStructConfig($config, $container);

        // EXIST MORE DEFINITIONS
        if ($config->has('definitions')) {
            static::addDefinitions($config, $container);
        }

        return $config;
    }

    public static function addDefinitions(
        NoodlehausConfig $config,
        ContainerInterface $container,
    ): void {
        $definitions = $config->get('definitions');
        if (!is_array($definitions)) {
            $container->get("whoops");
            throw new BadStructBasicConfig(
                "definitions key must be array",
                1,
            );
        }
        $path = $container->get('path');
        foreach ($definitions as $file) {
            $pathFile = "{$path}/{$file}";
            if (!file_exists($pathFile)) {
                $container->get("whoops");
                throw new BadStructBasicConfig(
                    "Not exists definitions file: {$pathFile}",
                    1,
                );
            }
            $values = require $pathFile;
            foreach ($values as $alias => $factory) {
                $container->set($alias, $factory);
            }
        }
    }

    public static function checkStructConfig(NoodlehausConfig $config, ContainerInterface $container)
    {
        if (
            !$config->has("env")
            || !in_array($config->get("env"), static::$allowed_environment, true)
        ) {
            $container->get("whoops");
            throw new BadStructBasicConfig(
                "Not Found Config 'env', please create 'env' key in config and use values 'local' or 'production'",
                1,
            );
        }
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
