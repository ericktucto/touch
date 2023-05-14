<?php

namespace Touch;

use Clockwork\Support\Vanilla\Clockwork;
use DI\Container;
use League\Route\Router;
use Lune\Http\Emitter\ResponseEmitter as Emitter;
use Psr\Http\Message\ServerRequestInterface;
use Touch\Core\Kernel;

class Application
{
    protected static Kernel $kernel;
  protected Clockwork $clockwork;

    public function __construct(
      protected Router $router,
      protected ServerRequestInterface $server,
    ) {
      if (static::$kernel instanceof Kernel) {
        if ($this->isLocal()) $this->createServicesToDevelopment();
      }
    }

    protected function isLocal(): bool
    {
      return $this->config("env") == "local";
    }

    public function config(?string $key = null, $default = null)
    {
      /** @var \Noodlehaus\Config $config */
      $config = $this->getContainer()->get("config");
      return match ($key) {
        null => $config,
        "*" => $config->all(),
        default => $config->get($key, $default)
      };
    }

    protected function createServicesToDevelopment()
    {
      $whoops = new \Whoops\Run;
      $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
      $whoops->register();
      $this->clockwork = $this->getContainer()->get(Clockwork::class);
    }

    public static function create(Kernel $kernel): Application
    {
      static::$kernel = $kernel;
      /** @var Application $app */
      $app = static::$kernel->getContainer()->make(Application::class);
      return $app;
    }

    public static function getContainer(): Container
    {
      return static::$kernel->getContainer();
    }

    public function route()
    {
        return $this->router;
    }

    public function run()
    {
        $response = $this->router->handle($this->server);
      if ($this->isLocal()) $this->clockwork->requestProcessed();
        (new Emitter())->emit($response);
    }
}
