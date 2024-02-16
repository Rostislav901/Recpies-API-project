<?php
namespace App\Service;
use App\Repository\CuisineRepository;
use App\Model\Cuisine;
use App\Utils\Mapper\CuisineModelMapper;

class CuisineService
{
    public function __construct(private CuisineRepository $repository,
                                private CuisineModelMapper $cuisineModelMapper )
    {
    }

    public function getCuisines() : Cuisine
    {
         $cuisines = $this->repository->findByTitleAsc();
         $elems = array_map([$this->cuisineModelMapper,"mapCuisineItem"],$cuisines);
         return  new Cuisine($elems);
    }


}
