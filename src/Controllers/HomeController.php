<?php

namespace Touch\Controllers;

use GuzzleHttp\Psr7\ServerRequest;
use Touch\Http\EngineTemplate;
use Touch\Http\Response;

class HomeController
{
  public function __construct(protected EngineTemplate $view)
  {
  }

  public function index(ServerRequest $request)
  {
    $nombre = request($request)->query("nombre", "¿cual es tu nombre ?");

    return Response::html($this->view->render("index", compact("nombre")));
  }
  public function enviar(ServerRequest $request)
  {
    $name = request($request)->body("name");
    return Response::json(["message" => "Hola {$name}"]);
  }
}
