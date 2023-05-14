<?php

namespace Touch\Core\Factories;

use Clockwork\Support\Vanilla\Clockwork as VanillaClockwork;
use Psr\Container\ContainerInterface;
use Touch\Http\Response;
use Touch\Http\Router;

class Clockwork
{
  public static function create(ContainerInterface $container)
  {
    /** @var VanillaClockwork $clockwork */
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
