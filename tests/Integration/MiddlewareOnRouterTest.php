<?php

namespace Touch\Tests\Integration;

use GuzzleHttp\Psr7\ServerRequest;
use League\Route\RouteGroup;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Touch\Application;
use Touch\Core\Kernel;
use Touch\Http\Response;
use Touch\Tests\Integration\Fixtures\Middlewares\ApiKeyMiddleware;
use Touch\Tests\Integration\Fixtures\Middlewares\AuthMiddleware;
use Touch\Tests\Integration\TestCase;

class MiddlewareOnRouterTest extends TestCase
{
    #[Test]
    public function use_container_to_create_middleware(): void
    {
        $kernel = new Kernel('./tests/Integration/Fixtures/config.yml');
        $app = Application::create($kernel);

        $app->route()
            ->middleware(AuthMiddleware::class)
            ->get(
                '/api/hello',
                fn() => Response::json(['message' => 'hello']),
            );

        $request = new ServerRequest(
            'GET',
            '/api/hello',
            ['Authorization' => 'Bearer token'],
        );
        $this->assertEquals(200, $app->handle($request)->getStatusCode());
    }

    #[Test]
    public function denied_access(): void
    {
        $kernel = new Kernel('./tests/Integration/Fixtures/config.yml');
        $app = Application::create($kernel);

        $app->route()
            ->get(
                '/api/hello',
                fn() => Response::json(['message' => 'hello']),
            )->middleware(new AuthMiddleware());

        $request = new ServerRequest('GET', '/api/hello');
        $response = $app->handle($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(401, $response->getStatusCode());

        $string = $response->getBody()->getContents();
        $data = json_decode($string, true);
        $this->assertJsonValueEquals($data, '.message', 'Unauthorized');
    }

    #[Test]
    public function allowed_access(): void
    {
        $kernel = new Kernel('./tests/Integration/Fixtures/config.yml');
        $app = Application::create($kernel);

        $app->route()
            ->middleware(new AuthMiddleware())
            ->get(
                '/api/hello',
                fn() => Response::json(['message' => 'hello']),
            );

        $request = new ServerRequest('GET', '/api/hello', [
            'Authorization' => 'Bearer token',
        ]);
        $response = $app->handle($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $string = $response->getBody()->getContents();
        $data = json_decode($string, true);
        $this->assertJsonValueEquals($data, '.message', 'hello');
    }

    #[Test]
    public function middleware_on_group(): void
    {
        $kernel = new Kernel('./tests/Integration/Fixtures/config.yml');
        $app = Application::create($kernel);

        /*
        $app->route()
            ->group(
                '/api',
                fn(RouteGroup $group) => $group->get(
                    '/hello',
                    fn() => Response::json(['message' => 'hello']),
                ),
            )
            ->middleware($app->getContainer()->get(AuthMiddleware::class))
        */
        $app->route()
            ->group(
                '/api',
                fn(RouteGroup $group) => $group->get(
                    '/hello',
                    fn() => Response::json(['message' => 'hello']),
                ),
                AuthMiddleware::class,
            )
        ;
        $app->route()
            ->get(
                '/api/world',
                fn() => Response::json(['message' => 'world']),
            );

        $request = new ServerRequest(
            'GET',
            '/api/hello',
            ['Authorization' => 'Bearer token'],
        );
        $response = $app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertJsonValueEquals($data, '.message', 'hello');

        $request = new ServerRequest(
            'GET',
            '/api/world',
        );
        $response = $app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertJsonValueEquals($data, '.message', 'world');
    }


    #[Test]
    public function add_middleware_to_group(): void
    {
        $kernel = new Kernel('./tests/Integration/Fixtures/config.yml');
        $app = Application::create($kernel);

        // AFTER
        $app->route()
            ->group(
                '/api/v1',
                fn(RouteGroup $group) => $group->get(
                    '/hello',
                    fn() => Response::json(['message' => 'hello']),
                ),
            )
            ->middleware($app->getContainer()->get(AuthMiddleware::class));

        // BY PARAMETER
        // NAMECLASS
        $app->route()
            ->group(
                '/api/v2',
                fn(RouteGroup $group) => $group->get(
                    '/world',
                    fn() => Response::json(['message' => 'hello']),
                ),
                ApiKeyMiddleware::class,
            );

        // MULTIPLE MIDDLEWARE
        $app->route()
            ->group(
                '/api/v3',
                fn(RouteGroup $group) => $group->get(
                    '/users',
                    fn() => Response::json(['message' => 'hello']),
                ),
                [AuthMiddleware::class,ApiKeyMiddleware::class],
            );

        $request = new ServerRequest(
            'GET',
            '/api/v1/hello',
            ['Authorization' => 'Bearer token'],
        );
        $response = $app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());

        $request = new ServerRequest(
            'GET',
            '/api/v2/world',
            ['X-API-KEY' => 'key'],
        );
        $response = $app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());


        $request = new ServerRequest(
            'GET',
            '/api/v3/users',
            [
                'X-API-KEY' => 'key',
                'Authorization' => 'Bearer token',
            ],
        );
        $response = $app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
