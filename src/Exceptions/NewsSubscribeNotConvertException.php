<?php

namespace App\Exceptions;

use RuntimeException;

class NewsSubscribeNotConvertException extends RuntimeException
{
     public function __construct()
     {
         parent::__construct("error during conversion");
     }
}
