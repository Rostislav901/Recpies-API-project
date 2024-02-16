<?php

namespace App\Tests\Service;

use App\Entity\Cuisine;
use App\Model\CuisineItem;
use App\Repository\CuisineRepository;
use App\Service\CuisineService;
use App\Model\Cuisine as Result;
use App\Utils\Mapper\CuisineModelMapper;
use PHPUnit\Framework\TestCase;

class CuisineServiceTest extends TestCase
{

    public function testGetCuisines()
    {
        $cuisineRepository = $this->createMock(CuisineRepository::class);
        $cuisineMapper = $this->createMock(CuisineModelMapper::class);
        $cuisine1 = (new Cuisine())->setTitle("first-test");
        $cuisine2 = (new Cuisine())->setTitle("second-test");
        $cuisines = [$cuisine1,$cuisine2];
        $cuisineRepository->expects($this->once())
                            ->method("findByTitleAsc")
                            ->willReturn($cuisines);

        $cuisineMapper->expects($this->exactly(2))
                            ->method("mapCuisineItem")
                            ->withConsecutive([$cuisine1],[$cuisine2])
                            ->willReturn((new CuisineItem())->setTitle("test"));

        $actual = (new CuisineService($cuisineRepository,$cuisineMapper))->getCuisines();

        $this->assertEquals(Result::class,get_class($actual));
        $this->assertEquals(CuisineItem::class,get_class($actual->getCuisines()[0]));
        $this->assertCount(2,$actual->getCuisines());
        $this->assertEquals("test",$actual->getCuisines()[0]->getTitle());
    }
}
