<?php

namespace Touch\Controllers;

use Illuminate\Database\ConnectionResolverInterface;
use Touch\Http\EngineTemplate;
use Touch\Http\Request;
use Touch\Http\Response;
use Touch\Models\Company;

class CompanyController
{
  public function __construct(
    protected ConnectionResolverInterface $conn,
    protected EngineTemplate $view
  ) {
  }

  public function index(Request $request)
  {
    $companies = Company::take(10)->get();

    return Response::html(
      $this->view->render("companies.index", compact("companies"))
    );
  }
}
