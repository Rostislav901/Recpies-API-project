<?php

namespace App\Exceptions;

use RuntimeException;

class UserExistsException extends RuntimeException
{
      public function __construct()
      {
          parent::__construct("user already exist");
      }
}
