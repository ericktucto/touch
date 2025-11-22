<?php

namespace Touch\Core\Clockwork;

class ApiController
{
    public function index($request, $response, $args)
    {
        return $response->json(
            clock()->getMetadata($args["request"]) ?? [],
            200,
            JSON_FORCE_OBJECT
        );
    }
}
