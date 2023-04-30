<?php

namespace Touch\Controllers\Traits;

trait HasViewTwig
{
  protected function view(string $view, array $data = [])
  {
    $view = str_replace(".", "/", $view) . ".twig";
    /** @var \Twig\Environment $twig */
    $twig = app("twig");
    return $twig->render($view, $data);
  }
}
