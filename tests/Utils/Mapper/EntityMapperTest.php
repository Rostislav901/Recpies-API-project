<?php

namespace App\Tests\Utils\Mapper;

use App\Entity\Cuisine;
use App\Entity\Recipe;
use App\Entity\Review;
use App\Entity\User;
use App\Model\RecipeManager\RequestRecipeModel;
use App\Model\Registration;
use App\Model\SubmitReview;
use App\Tests\Utils\AbstractMapperTestCase;
use App\Utils\Mapper\EntityMapper;

class EntityMapperTest extends AbstractMapperTestCase
{

    public function testMapRecipe()
    {
          $recipe = new Recipe();
          $cuisine = (new Cuisine())->setTitle("cuisine");
          $recipeModel = (new RequestRecipeModel())->setTitle("TEST TEST")->setDescription("testDescription")
                         ->setMainImage("testImg")->setIngredients(["test"])->setSteps(["steps"])->setImages(["images"])
                            ->setCuisineId(8);

          $this->security->expects($this->once())
                            ->method("getUser")
                            ->willReturn(new User());

          $this->cuisineRepository->expects($this->once())
                            ->method("find")
                            ->with(8)
                            ->willReturn($cuisine);

        $actual =  (new EntityMapper(cuisineRepository: $this->cuisineRepository, security: $this->security))
                            ->mapRecipe($recipe,$recipeModel);

        $this->assertEquals(Recipe::class,get_class($actual));
        $this->assertEquals("test-test",$recipe->getSlug());
        $this->assertEquals("cuisine",$recipe->getCuisine()->getTitle());
        $this->assertEquals(["images"],$recipe->getImages());
    }

    public function testMapReview()
    {
         $review = new Review();

         $submitReview = (new SubmitReview())->setText("test")->setRating(9);

        (new EntityMapper())->mapReview($review,$submitReview);

        $this->assertEquals("test",$review->getText());
        $this->assertEquals(9,$review->getRating());
    }

    public function testMapUserEntity()
    {
         $request = (new Registration())->setEmail("test-email")->setFirstName("John")->setLastName("Don")
                                        ->setCountry("testCountry")->setAboutMe("aboutMe")->setPassword("test");
         $this->hasher->expects($this->once())
                                ->method("hashPassword")
                                ->withAnyParameters()
                                ->willReturn("***password***");

        /* @var User $actual */
         $actual = (new EntityMapper(hasher: $this->hasher))->mapUserEntity($request);
         $this->assertEquals(User::class,get_class($actual));
         $this->assertEquals("test-email",$actual->getEmail());
         $this->assertEquals("***password***",$actual->getPassword());
         $this->assertEquals("testCountry",$actual->getCountry());

    }
}
