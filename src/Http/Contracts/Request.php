<?php

namespace Touch\Http\Contracts;

interface Request
{
    public function query(string $input, $default = null);
    public function body(string $input, mixed $default = null);
}

