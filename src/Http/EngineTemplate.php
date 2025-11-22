<?php

namespace Touch\Http;

use Twig\Environment;
use Touch\Http\Response;

class EngineTemplate
{
    public function __construct(protected Environment $twig)
    {
    }
    public function render(string $view, array $data = [])
    {
        $name = str_replace(".", "/", $view) . ".twig";
        $view = $this->twig->render($name, $data);
        return Response::html($view);
    }
}
