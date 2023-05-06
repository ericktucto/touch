<?php

namespace Touch\Controllers;

use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Database\ConnectionResolverInterface;
use Touch\Http\EngineTemplate;
use Touch\Http\Response;
use Touch\Models\Company;

class CompanyController
{
  public function __construct(
    protected ConnectionResolverInterface $conn,
    protected EngineTemplate $view
  ) {
  }

  public function index(ServerRequest $request)
  {
    $companies = Company::take(10)->get();

    return Response::html(
      $this->view->render("companies.index", compact("companies"))
    );
  }
}
