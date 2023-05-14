<?php

namespace Touch;

use Clockwork\Support\Vanilla\Clockwork;
use DI\Container;
use Lune\Http\Emitter\ResponseEmitter as Emitter;
use Psr\Http\Message\ServerRequestInterface;
use Touch\Core\Clockwork\DataSource\ApplicationDataSource;
use Touch\Core\Kernel;
use Touch\Http\Router;

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
      /** @var Clockwork $clockwork */
      $clockwork = $this->getContainer()->get(Clockwork::class);
      $this->clockwork = $clockwork;
      $this->clockwork
        ->addDataSource(
          new ApplicationDataSource($this->router, $this->getContainer())
        );
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
      // send response
      // TODO: METHOD_NOT_ALLOWED
      $response = $this->router->handle($this->server);
      (new Emitter())->emit($response);

      // using clockwork
      if ($this->isLocal()) $this->clockwork->requestProcessed();
    }
}
