<?php

namespace App\Exceptions;

use RuntimeException;

class UserReviewNotExistsException extends RuntimeException
{
      public function __construct()
      {
          parent::__construct("Review not exist");
      }
}
