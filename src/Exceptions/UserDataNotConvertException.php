<?php

namespace App\Exceptions;

use RuntimeException;

class UserDataNotConvertException extends RuntimeException
{
     public function __construct()
     {
         parent::__construct("error during conversion userdata");
     }
}
