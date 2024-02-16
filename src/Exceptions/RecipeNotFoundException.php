<?php

namespace App\Exceptions;

use RuntimeException;

class RecipeNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Recipe  not found ");
    }
}
