<?php

namespace Touch\Core\Factories;

use Clockwork\Support\Vanilla\Clockwork as VanillaClockwork;
use League\Route\Router;
use Psr\Container\ContainerInterface;
use Touch\Http\Response;

class Clockwork
{
  public static function create(ContainerInterface $container)
  {
    $clockwork = VanillaClockwork::init(["register_helpers" => true]);

    $container
      ->get(Router::class)
      ->get(
        "/__clockwork/{request}",
        fn($request, $args) => Response::json(
          $clockwork->getMetadata($args["request"]) ?? [],
          200,
          JSON_FORCE_OBJECT
        )
      );
    return $clockwork;
  }
}
