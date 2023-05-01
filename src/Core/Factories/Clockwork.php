<?php

namespace Touch\Core\Factories;

use Clockwork\Support\Vanilla\Clockwork as VanillaClockwork;
use Illuminate\Database\Eloquent\Model;
use IPub\SlimRouter\Routing\Router;
use Psr\Container\ContainerInterface;
use Touch\Core\Clockwork\DataSource\EloquentDataSource;
use Touch\Core\Clockwork\DataSource\TwigDataSource;
use Twig\Environment;

class Clockwork
{
  public static function create(ContainerInterface $container)
  {
    $clockwork = VanillaClockwork::init(["register_helpers" => true]);

    $clockwork->addDataSource(self::eloquentDataSource());

    if (!$container->has("twig")) {
      dd($container);
    }
    $clockwork->addDataSource(self::twigDataSource($container->get("twig")));

    $container
      ->get(Router::class)
      ->get(
        "/__clockwork/{request:.+}",
        fn($request, $response, $args) => $response->json(
          $clockwork->getMetadata($args["request"]) ?? [],
          200,
          JSON_FORCE_OBJECT
        )
      );
    return $clockwork;
  }

  protected static function eloquentDataSource()
  {
    $dataSource = new EloquentDataSource(
      Model::getConnectionResolver(),
      Model::getEventDispatcher()
    );
    $dataSource->listenToEvents();
    return $dataSource;
  }

  protected static function twigDataSource(Environment $twig)
  {
    $dataSource = new TwigDataSource($twig);
    $dataSource->listenToEvents();
    return $dataSource;
  }
}
