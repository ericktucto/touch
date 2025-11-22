<?php

namespace Touch\Core\Factories;

use Noodlehaus\Config as NoodlehausConfig;
use Psr\Container\ContainerInterface;
use Touch\Exceptions\BadStructBasicConfig;
use Touch\Exceptions\NotExistsConfig;

class Config
{
    protected static array $allowed_environment = ["local", "production"];

    public static function create(string $path, ContainerInterface $container)
    {
        static::checkExistsConfig($path, $container);
        $config = NoodlehausConfig::load($path);
        static::checkStructConfig($config, $container);
        return $config;
    }

    public static function checkStructConfig(NoodlehausConfig $config, ContainerInterface $container)
    {
        if (
            !$config->has("env")
            || !in_array($config->get("env"), static::$allowed_environment)
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
