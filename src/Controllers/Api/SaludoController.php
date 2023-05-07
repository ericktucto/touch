<?php

namespace Touch\Controllers\Api;

use Touch\Http\Request;
use Touch\Http\Response;

class SaludoController
{
  public function index(Request $request)
  {
    $name = $request->body("name");

    return Response::json(["message" => "Hola {$name}"]);
  }
}

