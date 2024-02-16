<?php

namespace App\Exceptions;

use RuntimeException;

class ImagesNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Images not found ");
    }
}
