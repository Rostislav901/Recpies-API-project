<?php

namespace App\Exceptions;

use RuntimeException;

class ChangeReviewAccessException extends RuntimeException
{
      public function __construct()
      {
          parent::__construct("Access denied. You cannot change reviews that are not yours");
      }
}
