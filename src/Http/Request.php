<?php

namespace Touch\Http;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use stdClass;

class Request extends ServerRequest implements ServerRequestInterface
{
    public function query(?string $input = null, $default = null)
    {
        $query = $this->getQueryParams();

        if (is_null($input)) {
            return $query;
        }

        return $query[$input] ?? $default;
    }

    public function body(?string $input = null, $default = null)
    {
        $body = $this->getBody();
        $data = json_decode($body->read($body->getSize()));

        if (is_null($input)) {
            return is_null($data) ? new stdClass() : $data;
        }

        if (is_null($data) || !property_exists($data, $input)) {
            return $default;
        }

        return $data->{$input};
    }
}
