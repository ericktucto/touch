<?php

namespace Touch\Exceptions;

use Exception;

class NotExistsConfig extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct("Not exists config.yml on root project.", $code, $previous);
    }
}
