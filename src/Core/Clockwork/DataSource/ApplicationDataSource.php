<?php

namespace Touch\Core\Clockwork\DataSource;

use Clockwork\DataSource\DataSourceInterface;
use Clockwork\Request\Request;
use League\Route\Dispatcher;
use Psr\Container\ContainerInterface;
use Touch\Http\Router;

class ApplicationDataSource implements DataSourceInterface
{
  public function __construct(
    protected Router $router,
    protected ContainerInterface $container
  ) {
  }

  public function resolve(Request $request)
  {
    $request->controller = $this->getController($request);
  }

  protected function getController(Request $request)
  {
    $dispatcher = new Dispatcher($this->router->getRouteData());
    $dispatcher->setStrategy($this->router->getStrategy());

    $match = $dispatcher->dispatch($request->method, $request->uri);
    $callable = $match[1]->getCallable($this->container);

    // if use magic method __invoke
    if (is_object($callable)) {
      return get_class($callable);
    }

    // if use syntax [controller, method] or "controller::method"
    if (is_array($callable)) {
      [$controller, $method] = $callable;
      return get_class($controller) . "@{$method}";
    }

    // if use anonymous function
    return "Closure";
  }

  public function extend(Request $request)
  {
  }

  public function reset()
  {
  }
}
