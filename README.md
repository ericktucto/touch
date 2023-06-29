# Touch
Microframework PHP, create to web applications with Touch

## Table of contents

  - [Install with Composer](#install-with-composer)
  - [Steps to create basic application](#steps-to-create-basic-application)
    - [1. Create config.yml](#1-create-configyml)
    - [2. Create index.php file](#2-create-indexphp-file)
  - [Integrated Packages](#integrated-packages)
  - [Ecosystem](#ecosystem)
    - [Routing](#routing)

## Install with Composer
```bash
composer require ericktucto/touch
```

## Steps to create basic application

### 1. Create `config.yml`

```yml
env: local # local or production
```

### 2. Create index.php file

```php
<?php
require __DIR__ . '/../vendor/autoload.php';

use Touch\Application;
use Touch\Core\Kernel;
use Touch\Http\Response;

$app = Application::create(new Kernel());

$app->route()->get("/", fn() => Response::html("Hello world!!!"));
$app->run();
```

## Integrated packages
  1. [PHP Dig][phpdi-doc] (dependency injection container)
  2. [Routing by thephpleague][routing-doc] (use psr-7 and psr-15 to middleware)
  3. [Eloquent][eloquent-doc] (ORM's Laravel)
  4. [Twig][twig-doc] (engine template of Symfony)
  5. [Clockwork][clockwork-doc] (debugger console)
  6. [Valitron][valitron-doc] (validation data request)

## Ecosystem
### Routing
Create your routing web applications

```php
<?php
// code ...
$app->route()->get("/", fn () => Response::html("My index"));
```
To more infor, you'll visit [docs][routing-doc] of The php league's Routing. But Touch have some interesed features.

#### Response class
Routing always need a function return a variable that implements `Psr\Http\Message\ResponseInterface` ([see doc](https://route.thephpleague.com/5.x/usage/)). To make returning response, exists `Touch\Http\Response`

```php
<?php
// code ...
// return html or text plain, $status is optional and 200 is default value
$app->route("/html", fn () => Response::html("<h3>My first app</h3>", 200));

// return json, $status is optional and 200 is default value
$app->route("/api/v1/json", fn () => Response::json(["message" => "It's okay"], 200);
// first argument can be a object
```
#### Grouping routes
Routing can group routes ([see docs](https://route.thephpleague.com/5.x/routes/)), but you have `Touch\Http\Route` to group on class.

```php
<?php
// index file
$app->group('/admin', new AdminRoutes);
// AdminRoutes.php
use League\Route\RouteGroup;
use Touch\Http\Response;
use Touch\Http\Route;

class AdminRoutes extends Route
{
    protected function routes(RouteGroup $group)
    {
        $group->get('/users', [UserController::class, "create"]);
        $group->get('/list-users', fn() => Response::html("<!-- list users -->"));
    }
}
// routes created
// GET /admin/users -> create method of UserController class
// GET /admin/list-users -> anonymous closure
```

[phpdi-doc]: https://php-di.org
[routing-doc]: https://route.thephpleague.com/
[eloquent-doc]: https://packagist.org/packages/illuminate/database
[twig-doc]: https://twig.symfony.com/
[clockwork-doc]: https://underground.works/clockwork/
[valitron-doc]: https://github.com/vlucas/valitron
