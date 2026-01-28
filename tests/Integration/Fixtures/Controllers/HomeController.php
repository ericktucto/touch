<?php

namespace Touch\Tests\Integration\Fixtures\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Touch\Http\Response;

class HomeController
{
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return Response::json(['message' => 'hello']);
    }

    public function show(
        ServerRequestInterface $request,
    ): ResponseInterface {
        return Response::html('<h1>hello erick</h1>');
    }
}
