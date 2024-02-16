<?php

namespace App\Model;

class Cuisine
{
    /**
     * @var CuisineItem[]$cuisines
     */
    private array $cuisines;

    /**
     * @param CuisineItem[]$cuisines
     */
    public function __construct(array $cuisines)
    {
        $this->cuisines = $cuisines;
    }

    /**
     * @return CuisineItem[]
     */
    public function getCuisines(): array
    {
        return $this->cuisines;

    }
}
