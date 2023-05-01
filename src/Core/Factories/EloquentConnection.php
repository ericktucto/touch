<?php

namespace Touch\Core\Factories;

use Clockwork\Support\Vanilla\Clockwork;
use Illuminate\Database\{ Connection, ConnectionResolver };
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use PDO;
use Psr\Container\ContainerInterface;
use Touch\Core\Clockwork\DataSource\EloquentDataSource;
use Touch\Core\Clockwork\Laravel\Dispatcher;

class EloquentConnection
{
  public static function create(ContainerInterface $container)
  {
    $pdo = new PDO("mysql:host=mysql;dbname=application", "erick", "1234");
    $conn = new Connection($pdo, "application", "");
    $conn->setQueryGrammar(new MySqlGrammar());

    $dispatcher = new Dispatcher();
    Model::setEventDispatcher($dispatcher);
    $conn->setEventDispatcher($dispatcher);

    $resolver = new ConnectionResolver([
      "mysql" => $conn,
    ]);
    $resolver->setDefaultConnection("mysql");
    Model::setConnectionResolver($resolver);

    $container->get(Clockwork::class)->addDataSource(self::getDataSource());
    return $resolver;
  }

  protected static function getDataSource()
  {
    $dataSource = new EloquentDataSource(
      Model::getConnectionResolver(),
      Model::getEventDispatcher()
    );
    $dataSource->listenToEvents();
    return $dataSource;
  }
}
