<?php

namespace Touch\Http;

use Twig\Environment;

class EngineTemplate
{
  public function __construct(protected Environment $twig)
  {
  }
  public function render(string $view, array $data = [])
  {
    $view = str_replace(".", "/", $view) . ".twig";
    return $this->twig->render($view, $data);
  }
}
