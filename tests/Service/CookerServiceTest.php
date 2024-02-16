<?php

namespace App\Tests\Service;

use App\Model\PublicCooker;
use App\Service\CookerService;
use App\Utils\Mapper\CookerModelMapper;
use PHPUnit\Framework\TestCase;

class CookerServiceTest extends TestCase
{

    public function testGetCookerById()
    {
         $mapper  = $this->createMock(CookerModelMapper::class);
         $publicCooker = (new PublicCooker())->setName("test")->setCookerInfo("testInfo")
                                                                    ->setCountry("Ukraine");
         $mapper->expects($this->once())
                            ->method("mapCooker")
                            ->with(1)
                            ->willReturn($publicCooker);

         $actual = (new CookerService($mapper))->getCookerById(1);

         $this->assertEquals(PublicCooker::class,get_class($actual));
         $this->assertEquals("test",$actual->getName());
         $this->assertEquals("Ukraine",$actual->getCountry());
    }
}
