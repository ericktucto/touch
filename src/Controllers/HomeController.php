<?php

namespace Touch\Controllers;

use GuzzleHttp\Psr7\ServerRequest;
use IPub\SlimRouter\Http\Response;

class HomeController
{
    use Traits\HasViewTwig;

    public function index(ServerRequest $request, Response $response)
    {
      $nombre = request($request)->query("nombre", "¿cual es tu nombre ?");

      return $response->html(
        $this->view("index", compact("nombre"))
      );
    }
    public function enviar(ServerRequest $request, Response $response)
    {
      $name = request($request)->body("name");
      return $response->json(["message" => "Hola {$name}"]);
    }
}
