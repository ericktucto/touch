<?php

namespace Touch\Controllers;

use Touch\Controllers\Traits\HasViewTwig;
//use Touch\Models\Company;

class HomeController extends Controller
{
    use HasViewTwig;

    public function index()
    {
      //$c = Company::first();
      $nombre = $this->request->query("nombre");
      echo $this->view("index", compact("nombre"));
    }
    public function enviar()
    {
      $password = $this->request->body("password");
      echo $this->view("enviar", compact("password"));
    }
}
