<?php

namespace App\Tests\Utils\Mapper;

use App\Entity\Cuisine;
use App\Model\CuisineItem;
use App\Tests\Utils\AbstractMapperTestCase;
use App\Utils\Mapper\CuisineModelMapper;
use PHPUnit\Framework\TestCase;

class CuisineModelMapperTest extends AbstractMapperTestCase
{

    public function testMapCuisineItem()
    {
        $entity = (new Cuisine())->setId(7)->setTitle("test")->setSlug("/test");

        $actual = (new CuisineModelMapper())->mapCuisineItem($entity);

        $this->assertEquals(CuisineItem::class,get_class($actual));
        $this->assertEquals(7,$actual->getId());
        $this->assertEquals("/test",$actual->getSlug());
    }
}
