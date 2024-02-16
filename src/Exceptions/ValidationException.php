<?php

namespace App\Exceptions;


use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("validationError");
    }

//    public function getViolationList(): ConstraintViolationListInterface
//    {
//        return $this->violationList;
//    }


}
