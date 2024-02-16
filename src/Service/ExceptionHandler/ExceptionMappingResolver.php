<?php

namespace App\Service\ExceptionHandler;

use InvalidArgumentException;

class ExceptionMappingResolver
{
    /**
     * @var ExceptionMapping[]$mappings
     */
    private array $mappings = [];

    /**
     * @param array $mappings
     */
    public function __construct(array $mappings)
    {

         foreach ($mappings as $class => $mapping)
         {
              if(empty($mapping["code"]))
              {
                  throw  new InvalidArgumentException("code wrong in $class");
              }
              $this->addMapping(
                                    $class,
                                    $mapping["code"],
                             $mapping["hidden"] ?? true,
                            $mapping["loggable"] ?? false
              );
         }
    }

    private function addMapping(string $class, int $code, bool $hidden, bool $loggable) : void
    {
           $this->mappings[$class] = new ExceptionMapping($code,$hidden,$loggable);
    }

    public function resolve(string $throwableClass) : ?ExceptionMapping
    {
        $found = null;
        foreach ($this->mappings as $class => $mapping)
        {
            if($throwableClass === $class || is_subclass_of($throwableClass,$class))
            {
                $found = $mapping;
                break;
            }
        }
        return  $found;
    }

}
