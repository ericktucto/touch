<?php

namespace Touch\Controllers;

use Touch\Http\EngineTemplate;
use Touch\Http\Request;
use Touch\Http\Response;

class HomeController
{
  public function __construct(protected EngineTemplate $view)
  {
  }

  public function index(Request $request)
  {
    $nombre = $request->query("nombre", "¿cual es tu nombre ?");

    return Response::html($this->view->render("index", compact("nombre")));
  }
  public function enviar(Request $request)
  {
    $name = $request->body("name");
    return Response::json(["message" => "Hola {$name}"]);
  }
}
