<?php

namespace Touch;

use Clockwork\Support\Vanilla\Clockwork;
use League\Route\Router;
use Psr\Http\Message\ServerRequestInterface;
use Touch\Core\Kernel;

class Application
{
    protected static Kernel $kernel;

    public function __construct(
      protected Router $router,
      protected ServerRequestInterface $server,
      protected Clockwork $clockwork,
    ) {
      $whoops = new \Whoops\Run;
      $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
      $whoops->register();
    }

    public static function create(Kernel $kernel): Application
    {
      static::$kernel = $kernel;
      /** @var Application $app */
      $app = static::$kernel->getContainer()->make(Application::class);
      return $app;
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
