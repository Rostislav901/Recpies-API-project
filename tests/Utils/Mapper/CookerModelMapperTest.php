<?php

namespace App\Tests\Utils\Mapper;

use App\Entity\Recipe;
use App\Entity\User;
use App\Model\PrivateCooker;
use App\Model\PublicCooker;
use App\Tests\Utils\AbstractMapperTestCase;
use App\Utils\Mapper\CookerModelMapper;
use DateTimeImmutable;

class CookerModelMapperTest extends AbstractMapperTestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository->expects($this->once())
            ->method("getFullNameById")
            ->with(1)
            ->willReturn("Test Name");
    }

    public function testMapCooker()
    {
        $time = new DateTimeImmutable();

        $user = (new User())->setAboutMe("testDescription")->setCountry("Ukraine")->setCreatedAt($time);

        $this->userRepository->expects($this->once())
                        ->method("getById")
                        ->with(1)
                        ->willReturn($user);

        $this->recipeRepository->expects($this->once())
                        ->method("getPublicRecipesByUser")
                        ->with($user)
                        ->willReturn([new Recipe()]);

        $this->methodHelper->expects($this->once())
                            ->method("calculateAverageRating")
                            ->with([new Recipe()])
                            ->willReturn(7.27);


        $actual = (new CookerModelMapper(userRepository: $this->userRepository,
                  recipeRepository: $this->recipeRepository,methodHelper: $this->methodHelper))->mapCooker(1);

        $this->assertEquals(PublicCooker::class,get_class($actual));
        $this->assertEquals($time->getTimestamp(),$actual->getRegistrationDate());
        $this->assertEquals("testDescription",$actual->getCookerInfo());
        $this->assertEquals(7.27,$actual->getRecipeAverageRating());
        $this->assertEquals(1,$actual->getRecipeCount());

    }

    public function testMapPrivateCooker()
    {
        $user = (new User())->setEmail("test@mail.com")->setId(1)
                ->setAboutMe("test")->setCountry("Ukraine")->setCreatedAt(new DateTimeImmutable());
        $recipes = [new Recipe()];
        $this->recipeRepository->expects($this->once())
            ->method("getPublicRecipesByUser")
            ->with($user)
            ->willReturn($recipes);

        $this->methodHelper->expects($this->once())
            ->method("calculateAverageRating")
            ->with($recipes)
            ->willReturn(7.27);

        $this->recipeRepository->expects($this->once())
            ->method("getCountByUser")
            ->with($user)
            ->willReturn(7);

        $this->recipeRepository->expects($this->once())
            ->method("getPrivateCountByUser")
            ->with($user)
            ->willReturn(3);

        $this->recipeRepository->expects($this->once())
            ->method("getPublicCountByUser")
            ->with($user)
            ->willReturn(4);

        $this->methodHelper->expects($this->once())
            ->method("getTotalReviewCount")
            ->with($recipes)
            ->willReturn(10);

        $actual = (new CookerModelMapper(userRepository: $this->userRepository,
            recipeRepository: $this->recipeRepository,methodHelper: $this->methodHelper))->mapPrivateCooker($user);

        $this->assertEquals(PrivateCooker::class,get_class($actual));
        $this->assertEquals("Ukraine",$actual->getCountry());
        $this->assertEquals("test@mail.com",$actual->getEmail());
        $this->assertEquals("Test Name",$actual->getFullName());
        $this->assertEquals(10,$actual->getTotalReviewCount());
        $this->assertEquals(4,$actual->getCountPublicRecipes());
        $this->assertEquals(7,$actual->getCountRecipes());

    }
}
