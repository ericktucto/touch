<?php

namespace Touch\Http\Interfaces;

interface RouteBuilderInterface
{
    /**
     * @param callable(\Psr\Http\Message\ServerRequestInterface,
     *                 \Psr\Http\Message\ResponseInterface): \Psr\Http\Message\ResponseInterface $callback
     */
    public function get(string $path, callable $callback): RouteBuilderInterface;
}
