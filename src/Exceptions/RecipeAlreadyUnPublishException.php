<?php

namespace App\Exceptions;

use RuntimeException;

class RecipeAlreadyUnPublishException extends RuntimeException
{
      public function __construct()
      {
          parent::__construct("recipe already unpublish");
      }
}
