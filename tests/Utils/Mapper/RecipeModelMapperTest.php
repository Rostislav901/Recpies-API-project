<?php

namespace App\Tests\Utils\Mapper;

use App\Entity\Cuisine;
use App\Entity\Recipe;
use App\Entity\User;
use App\Model\RecipeExtensive;
use App\Model\RecipeItem;
use App\Tests\Utils\AbstractMapperTestCase;
use App\Utils\Mapper\RecipeModelMapper;
use DateTimeImmutable;

class RecipeModelMapperTest extends AbstractMapperTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository->expects($this->once())
            ->method("getFullNameById")
            ->with(1)
            ->willReturn("Test Name");

        $this->reviewRepository->expects($this->once())
            ->method("getCountByRecipeId")
            ->with(3)
            ->willReturn(10);

        $this->reviewRepository->expects($this->once())
            ->method("getRating")
            ->with(3)
            ->willReturn(9);

    }

    public function testMapRecipeShortForm()
    {
        $recipeEntity = $this->map();

        $actual = (new RecipeModelMapper(reviewRepository: $this->reviewRepository,userRepository: $this->userRepository))
                            ->mapRecipeShortForm($recipeEntity);

        $this->assertEquals(RecipeItem::class,get_class($actual));
        $this->assertEquals(0.9,$actual->getRating());
        $this->assertEquals("Test Name",$actual->getCooker());
        $this->assertEquals("/test/slug/",$actual->getSlug());
        $this->assertEquals("testCuisine",$actual->getCuisine());
        $this->assertEquals($recipeEntity->getPublicationDate()->getTimestamp(),$actual->getPublicDate());
    }

    public function testMapRecipeExtendForm()
    {
        $recipeEntity = $this->map();
        $recipeEntity
            ->setSteps(["steps"])
            ->setIngredients(["ingredients"])
            ->setImages(["images"])
            ->setDescription("testDescription");

        $actual = (new RecipeModelMapper(reviewRepository: $this->reviewRepository,userRepository: $this->userRepository))
            ->mapRecipeExtendForm($recipeEntity);

        $this->assertEquals(RecipeExtensive::class,get_class($actual));
        $this->assertEquals(0.9,$actual->getRating());
        $this->assertEquals($recipeEntity->getPublicationDate()->getTimestamp(),$actual->getPublicationDate());
        $this->assertEquals(["steps"],$actual->getSteps());
        $this->assertEquals("testDescription",$actual->getDescription());
        $this->assertEquals("testCuisine",$actual->getCuisine());
    }


    public function map() : Recipe
    {
        $user = (new User())->setId(1);
        $cuisine = (new Cuisine())->setTitle("testCuisine");
        $time = new DateTimeImmutable();
        return (new  Recipe())
            ->setUser($user)
            ->setId(3)
            ->setTitle("test")
            ->setImage("testImg")
            ->setSlug("/test/slug/")
            ->setCuisine($cuisine)
            ->setPublicationDate($time);
    }


}
