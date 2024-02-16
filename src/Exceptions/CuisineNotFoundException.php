<?php

namespace App\Exceptions;

use RuntimeException;

class CuisineNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Cuisine with searching id not found ");

    }
}
