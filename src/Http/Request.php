<?php

namespace Touch\Http;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class Request extends ServerRequest implements ServerRequestInterface
{
  public function query(string $input, $default = null)
  {
    $query = $this->getQueryParams();
    return $query[$input] ?? $default;
  }

  public function body(string $input, $default = null)
  {
    $body = $this->getBody();
    $data = json_decode($body->read($body->getSize()));

    if (is_null($data) || !property_exists($data, $input)) {
      return $default;
    }

    return $data->{$input};
  }
}
