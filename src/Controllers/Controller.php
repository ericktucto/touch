<?php

namespace Touch\Controllers;

use Touch\Http\Contracts\Request;

abstract class Controller
{
    protected Request $request;
    public function __construct()
    {
        $this->request = app(Request::class);
    }
}
