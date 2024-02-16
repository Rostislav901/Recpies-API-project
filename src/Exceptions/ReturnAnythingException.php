<?php

namespace App\Exceptions;

use RuntimeException;

class ReturnAnythingException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Anything");

    }
}
