<?php

namespace Touch\Tests\Integration\Fixtures\Middlewares;

use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Touch\Http\Response;

class ApiKeyMiddleware implements MiddlewareInterface
{
    #[Override]
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $headers = $request->getHeader('X-API-KEY');
        if (count($headers) === 0) {
            return Response::json(['message' => 'Unauthorized'], 401);
        }
        return $handler->handle($request);
    }
}
