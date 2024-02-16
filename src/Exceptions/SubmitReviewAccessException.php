<?php

namespace App\Exceptions;

use RuntimeException;

class SubmitReviewAccessException extends RuntimeException
{
      public function __construct()
      {
          parent::__construct("Access denied. You cannot submit review on your recipes");
      }
}
