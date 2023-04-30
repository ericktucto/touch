<?php

namespace Touch\Controllers\Traits;

trait HasResponseJson
{
  public function json()
  {
    header('Content-Type: application/json; charset=utf-8');
  }
}
