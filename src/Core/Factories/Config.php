<?php

namespace Touch\Core\Factories;

use Noodlehaus\Config as NoodlehausConfig;

class Config
{
  public static function create(string $path)
  {
    return NoodlehausConfig::load($path);
  }
}
