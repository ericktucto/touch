<?php

namespace Touch;

use Bramus\Router\Router;
use Clockwork\DataSource\EloquentDataSource;
use Clockwork\Support\Vanilla\Clockwork;
use DI\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use PDO;
use Touch\Core\Eloquent\Dispatcher as EloquentDispatcher;

class Application
{
    protected static ?Container $container;
    protected Clockwork $clockwork;

    public function __construct(protected Router $router)
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
        clock()->addDataSource($dataSource);
    }

    protected function connectionDatabase()
    {
        $dispatcher = $this->createEventDispatcher();
        Model::setEventDispatcher($dispatcher);

        $pdo = new PDO("mysql:host=mysql;dbname=application", "erick", "1234");
        $conn = new Connection($pdo, 'application', '');
        $conn->setQueryGrammar(new MySqlGrammar());
        $resolver = new ConnectionResolver([
            "mysql" => $conn,
        ]);
        $resolver->setDefaultConnection("mysql");
        Model::setConnectionResolver($resolver);
    }

    protected function createEventDispatcher() : Dispatcher
    {
        $dispatcher = new EloquentDispatcher();
        return $dispatcher;
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
        $this->router->get('/__clockwork/{request:.+}', function ($request) {
            header('Content-Type: application/json');
            $jsonArray = $this->clockwork->getMetadata($request);
            echo json_encode($jsonArray);
        });
        $this->router->run(function () {
            $this->clockwork->requestProcessed();
        });
    }
}
