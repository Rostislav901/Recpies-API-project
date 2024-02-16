<?php

namespace App\Utils\Mapper;

use App\Model\CuisineItem;
use App\Entity\Cuisine;
class CuisineModelMapper extends Mapper
{

     public function mapCuisineItem(Cuisine $cuisineEntity ) : CuisineItem
     {
         return  (new CuisineItem())
                        ->setId($cuisineEntity->getId())
                        ->setTitle($cuisineEntity->getTitle())
                        ->setSlug($cuisineEntity->getSlug());
     }

}
