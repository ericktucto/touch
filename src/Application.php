<?php

namespace Touch;

use IPub\SlimRouter\Routing\Router;
use Clockwork\Support\Vanilla\Clockwork;
use DI\Container;
use Psr\Http\Message\ServerRequestInterface;

class Application
{
    protected static ?Container $container;

    public function __construct(
      protected Router $router,
      protected ServerRequestInterface $server,
      protected Clockwork $clockwork,
    ) {
    }

    public static function setContainer(Container $container)
    {
        static::$container = $container;
    }

    public static function getContainer(): ?Container
    {
        return static::$container;
    }


    public function route()
    {
        return $this->router;
    }

    public function run()
    {
        $response = $this->router->handle($this->server);
        $this->clockwork->requestProcessed();
        echo $response->getBody();
    }
}
