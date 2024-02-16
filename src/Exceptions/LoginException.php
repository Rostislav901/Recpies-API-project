<?php

namespace App\Exceptions;

use RuntimeException;

class LoginException extends RuntimeException
{
      public function __construct()
      {
          parent::__construct("userdata not exist");
      }
}
