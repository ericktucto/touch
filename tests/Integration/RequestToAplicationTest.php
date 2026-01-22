<?php

namespace Touch\Tests\Integration;

use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Touch\Application;
use Touch\Core\Kernel;
use Touch\Http\Response;
use Touch\Tests\Integration\Fixtures\Controllers\HomeController;
use Touch\Tests\Integration\TestCase;

class RequestToAplicationTest extends TestCase
{
    #[Test]
    public function response_hello(): void
    {
        $kernel = new Kernel('./tests/Integration/Fixtures/config.yml');
        $app = Application::create($kernel);
        $app->route()
            ->get(
                '/api/hello',
                fn() => Response::json(['message' => 'hello']),
            );
        $request = new ServerRequest('GET', '/api/hello');
        $response = $app->handle($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $string = $response->getBody()->getContents();
        $data = json_decode($string, true);
        $this->assertJsonValueEquals($data, '.message', 'hello');
    }

    #[Test]
    public function use_container_to_request(): void
    {
        $kernel = new Kernel('./tests/Integration/Fixtures');
        $app = Application::create($kernel);
        $app->route()
            ->get(
                '/api/hello',
                [HomeController::class, 'index'],
            );
        $request = new ServerRequest('GET', '/api/hello');
        $response = $app->handle($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $string = $response->getBody()->getContents();
        $data = json_decode($string, true);
        $this->assertJsonValueEquals($data, '.message', 'hello');
    }

    #[Test]
    public function show_index_template_twig(): void
    {
        $kernel = new Kernel('./tests/Integration/Fixtures');
        $app = Application::create($kernel);
        $app->route()
            ->get(
                '/',
                [HomeController::class, 'show'],
            );
        $request = new ServerRequest('GET', '/');
        $response = $app->handle($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $string = $response->getBody()->getContents();
        $this->assertStringContainsString('hello erick', $string);
    }
}
