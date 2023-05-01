<?php

namespace Touch\Core\Factories;

use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use PDO;
use Touch\Core\Clockwork\Laravel\Dispatcher;

class EloquentConnection
{
  public static function create()
  {
    $dispatcher = new Dispatcher();
    Model::setEventDispatcher($dispatcher);

    $pdo = new PDO("mysql:host=mysql;dbname=application", "erick", "1234");
    $conn = new Connection($pdo, "application", "");
    $conn->setQueryGrammar(new MySqlGrammar());
    $conn->setEventDispatcher($dispatcher);
    $resolver = new ConnectionResolver([
      "mysql" => $conn,
    ]);
    $resolver->setDefaultConnection("mysql");
    Model::setConnectionResolver($resolver);
    return $resolver;
  }
}
