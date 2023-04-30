<?php

namespace Touch\Http;

use GuzzleHttp\Psr7\ServerRequest;

class Request implements Contracts\Request
{
    public function __construct(protected ServerRequest $request)
    {}

    public function query(string $input, $default = null)
    {
        $query = $this->request->getQueryParams();
        return $query[$input] ?? $default;
    }

    public function body(string $input, $default = null)
    {
        $query = $this->request->getParsedBody();
        return $query[$input] ?? $default;
    }
}
