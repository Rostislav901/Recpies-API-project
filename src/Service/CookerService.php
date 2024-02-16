<?php

namespace App\Service;

use App\Model\PublicCooker;
use App\Utils\Mapper\CookerModelMapper;

class CookerService
{
     public function __construct(private  CookerModelMapper $cookerModelMapper)
     {
     }


     public function getCookerById(int $id) : PublicCooker
     {
          return  $this->cookerModelMapper->mapCooker($id);
     }



}
