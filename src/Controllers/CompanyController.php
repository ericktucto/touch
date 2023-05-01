<?php

namespace Touch\Controllers;

use GuzzleHttp\Psr7\ServerRequest;
use IPub\SlimRouter\Http\Response;
use Touch\Models\Company;

class CompanyController
{
    use Traits\HasViewTwig,
      Traits\HasConnectionDatabase;

    public function index(ServerRequest $request, Response $response)
    {
      $companies = Company::take(10)->get();

      return $response->html(
        $this->view("companies.index", compact("companies"))
      );
    }
}
