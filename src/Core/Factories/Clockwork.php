<?php

namespace Touch\Core\Factories;

use Clockwork\Support\Vanilla\Clockwork as VanillaClockwork;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Touch\Http\Response;
use Touch\Http\Router;

class Clockwork
{
    public static function create(ContainerInterface $container): VanillaClockwork
    {
        $path = $container->get("config")->get("app.project_dir");
        /** @var VanillaClockwork $clockwork */
        $clockwork = VanillaClockwork::init([
            "register_helpers" => true,
            "web" => [
                "enable" => '/__clockwork/app',
                "path" => $path . "/clockwork/web",
            ],
        ]);

        $container
            ->get(Router::class)
            ->get(
                "/__clockwork/app[/{file:.*}]",
                // @todo: return web
                fn(ServerRequestInterface $request): mixed => $clockwork->usePsrMessage($request, Response::html(''))->returnWeb(),
            );
        $container
        ->get(Router::class)
        ->get(
            "/__clockwork/{request:.+}",
            fn(ServerRequestInterface $request, array $args): ResponseInterface => Response::json(
                $clockwork->getMetadata($args["request"]) ?? [],
                200,
            ),
        );
        return $clockwork;
    }
}
