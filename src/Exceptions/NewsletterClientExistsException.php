<?php

namespace App\Exceptions;

use RuntimeException;

class NewsletterClientExistsException extends RuntimeException
{
      public function __construct()
      {
          parent::__construct("email already exist");
      }
}
