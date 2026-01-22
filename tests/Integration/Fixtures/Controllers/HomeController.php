<?php

namespace Touch\Tests\Integration\Fixtures\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Touch\Http\EngineTemplate;
use Touch\Http\Response;

class HomeController
{
    public function __construct(
        protected EngineTemplate $twig,
    ) {}

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return Response::json(['message' => 'hello']);
    }

    public function show(
        ServerRequestInterface $request,
    ): ResponseInterface {
        return $this->twig->render('index');
    }
}
