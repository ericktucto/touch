<?php

namespace Touch\Core\Factories;

use Clockwork\Support\Vanilla\Clockwork;
use Illuminate\Database\{Connection, ConnectionResolver};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use Illuminate\Database\Query\Grammars\SQLiteGrammar;
use PDO;
use Psr\Container\ContainerInterface;
use Touch\Core\Clockwork\DataSource\EloquentDataSource;
use Touch\Core\Clockwork\Laravel\Dispatcher;

class EloquentConnection
{
    public static function create(ContainerInterface $container)
    {
        $dispatcher = new Dispatcher();
        Model::setEventDispatcher($dispatcher);

      /** @var \Noodlehaus\Config $config */
        $config = $container->get("config");
        $databases = $config->get("databases", []);

        $default = static::getDefaultNameConnection($databases);
        $connections = static::createConnections($databases, $dispatcher);

        $resolver = new ConnectionResolver($connections);
        $resolver->setDefaultConnection($default);
        Model::setConnectionResolver($resolver);

        $container->get(Clockwork::class)->addDataSource(self::getDataSource());
        return $resolver;
    }

    protected static function getDefaultNameConnection(array $databases): string
    {
        if (array_key_exists("default", $databases)) {
            return $databases["default"];
        }
        $names = array_keys($databases);
        return array_filter($names, fn($name) => $name !== "default")[0];
    }

    protected static function createConnections(array $databases, Dispatcher $dispatcher): array
    {
        $connections = [];
        foreach ($databases as $connName => $configDb) {
          // continue if is default
            if ($connName === "default") {
                continue;
            }

            $driver = $configDb["driver"];

            $conn = match ($driver) {
                "mysql" => static::buildMysqlConnection(
                    $configDb
                ),
                "sqlite" => static::buildSqliteConnection(
                    $configDb
                ),
            };

            $conn->setEventDispatcher($dispatcher);
            $connections[$connName] = $conn;
        }

        return $connections;
    }

    protected static function buildMysqlConnection(array $config): Connection
    {
        $host = $config["host"];
        $dbname = $config["dbname"];
        $user = $config["user"];
        $password = $config["password"];
        $pdo = new PDO("mysql:host={$host};dbname={$dbname}", $user, $password);
        $conn = new Connection($pdo, $dbname);
        $conn->setQueryGrammar(new MySqlGrammar());
        return $conn;
    }

    protected static function buildSqliteConnection(array $config): Connection
    {
        $path = $config["path"];
        $pdo = new PDO("sqlite:{$path}");
        $conn = new Connection($pdo);
        $conn->setQueryGrammar(new SQLiteGrammar());
        return $conn;
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
