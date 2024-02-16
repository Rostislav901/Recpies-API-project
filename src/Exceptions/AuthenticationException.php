<?php

namespace App\Exceptions;
use RuntimeException;
class AuthenticationException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Authentication was failed. Check you username and password again. ");
    }
}
