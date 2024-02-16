<?php

namespace App\Service\ExceptionHandler;

class ExceptionMapping
{
    public function __construct(private int $code,private bool $hidden,private  bool $loggable)
    {

    }

    public static function fromCode(int $code) : static
    {
        return  new static($code,true,false);
    }
    public function getCode(): int
    {
        return $this->code;
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function isLoggable(): bool
    {
        return $this->loggable;
    }


}
