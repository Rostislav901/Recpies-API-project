<?php

namespace App\Exceptions;

use RuntimeException;

class RecipeAlreadyPublishException extends RuntimeException
{
      public function __construct()
      {
          parent::__construct("recipe already publish");
      }
}
