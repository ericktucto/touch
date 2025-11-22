<?php

namespace Touch\Core\Factories;

use Touch\Http\Request;

class ServerRequest
{
    public static function create()
    {
        $request = Request::fromGlobals();
        return new Request(
            $request->getMethod(),
            $request->getUri(),
            $request->getHeaders(),
            (string) $request->getBody(),
            $request->getProtocolVersion(),
            $request->getServerParams()
        );
    }
}
