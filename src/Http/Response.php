<?php

namespace Touch\Http;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

class Response
{
    public static function html(string $html, int $status = 200): GuzzleResponse
    {
        $response = new GuzzleResponse($status, ["Content-type" => "text/html"]);
        $response->getBody()->write($html);
        $response->getBody()->rewind();
        return $response;
    }

    public static function json(array|object $data, int $status = 200): GuzzleResponse
    {
        return new GuzzleResponse(
            $status,
            [ "Content-Type" => "application/json; charset=utf-8", ],
            json_encode($data),
        );
    }
}
