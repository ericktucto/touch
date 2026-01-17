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
    protected Kernel $kernel;
    protected Clockwork $clockwork;

    public function __construct(
        protected Router $router,
        protected ServerRequestInterface $server,
    ) {
    }

    protected function isLocal(): bool
    {
        return $this->config("env") == "local";
    }

    /**
     * @template T1
     * @return T1
     */
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
        $this->getContainer()->get("whoops");
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
      /** @var Application $app */
        $app = $kernel->getContainer()->make(Application::class);
        $app->kernel = $kernel;
        return $app;
    }

    public function getContainer(): Container
    {
        return $this->kernel->getContainer();
    }

    public function route(): Router
    {
        return $this->router;
    }

    public function run()
    {
        if ($this->isLocal()) {
            $this->createServicesToDevelopment();
        }
      // send response
      // TODO: METHOD_NOT_ALLOWED
        $response = $this->router->handle($this->server);
        (new Emitter())->emit($response);

      // using clockwork
        if ($this->isLocal()) {
            $this->clockwork->requestProcessed();
        }
    }
}
