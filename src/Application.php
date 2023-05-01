<?php

namespace Touch;

use IPub\SlimRouter\Routing\Router;
use Clockwork\Support\Vanilla\Clockwork;
use DI\Container;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use PDO;
use Psr\Http\Message\ServerRequestInterface;
use Touch\Core\Clockwork\ApiController as ClockworkController;
use Touch\Core\Clockwork\DataSource\{ EloquentDataSource, TwigDataSource };
use Touch\Core\Clockwork\Laravel\Dispatcher;

class Application
{
    protected static ?Container $container;
    protected Clockwork $clockwork;

    public function __construct(protected Router $router, protected ServerRequestInterface $server)
    {
        $this->connectionDatabase();
        $this->clockInit();
    }

    protected function clockInit()
    {
      $this->clockwork = Clockwork::init(['register_helpers' => true]);

      $dataSource = new EloquentDataSource(
        Model::getConnectionResolver(),
        Model::getEventDispatcher(),
      );
      $dataSource->listenToEvents();
      $this->clockwork->addDataSource($dataSource);

      $dataSource = new TwigDataSource(app("twig"));
      $dataSource->listenToEvents();
      $this->clockwork->addDataSource($dataSource);

      $this->router->get(
        "/__clockwork/{request:.+}",
        controller(ClockworkController::class, "index")
      );
    }

    protected function connectionDatabase()
    {
        $dispatcher = new Dispatcher();
        Model::setEventDispatcher($dispatcher);

        $pdo = new PDO("mysql:host=mysql;dbname=application", "erick", "1234");
        $conn = new Connection($pdo, 'application', '');
        $conn->setQueryGrammar(new MySqlGrammar());
        $conn->setEventDispatcher($dispatcher);
        $resolver = new ConnectionResolver([
            "mysql" => $conn,
        ]);
        $resolver->setDefaultConnection("mysql");
        Model::setConnectionResolver($resolver);
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
