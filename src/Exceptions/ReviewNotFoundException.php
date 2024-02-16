<?php

namespace App\Exceptions;

use RuntimeException;

class ReviewNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Review  not found ");
    }
}
