<?php

namespace App\Tests\Service;

use App\Entity\Recipe;
use App\Entity\User;
use App\Exceptions\RecipeNotFoundException;
use App\Model\RecipeExtensive;
use App\Model\RecipeItem;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use App\Service\RecipeService;
use App\Utils\Mapper\RecipeModelMapper;
use PHPUnit\Framework\TestCase;
use App\Model\Recipe as RecipeModel;

class RecipeServiceTest extends TestCase
{

    private RecipeRepository $recipeRepository;
    private RecipeModelMapper $recipeModelMapper;

    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->recipeRepository = $this->createMock(RecipeRepository::class);
        $this->recipeModelMapper = $this->createMock(RecipeModelMapper::class);
        $this->userRepository = $this->createMock(UserRepository::class);
    }

    public function testGetPublicRecipesByCookerThrowException()
    {
        $user = new User();

        $this->userRepository->expects($this->once())
            ->method("getById")
            ->with(1)
            ->willReturn($user);

        $this->recipeRepository->expects($this->once())
            ->method("getPublicRecipesByUser")
            ->with($user)
            ->willReturn([]);

        $this->expectException(RecipeNotFoundException::class);

        (new RecipeService($this->recipeRepository,$this->recipeModelMapper,$this->userRepository))->getPublicRecipesByCooker(1);


    }

    public function testGetPublicRecipesByCooker()
    {
        $user = new User();
        $recipe1 = (new Recipe())->setTitle("test-test");
        $recipe2 = (new Recipe())->setTitle("test-test");
        $recipes = [$recipe1,$recipe2];

        $this->userRepository->expects($this->once())
            ->method("getById")
            ->with(1)
            ->willReturn($user);

        $this->recipeRepository->expects($this->once())
            ->method("getPublicRecipesByUser")
            ->with($user)
            ->willReturn($recipes);

        $this->recipeModelMapper->expects($this->exactly(2))
            ->method("mapRecipeShortForm")
            ->withConsecutive([$recipe1],[$recipe2])
            ->willReturn((new RecipeItem())->setTitle("test-test")->setRating(5.5));

       $actual =  (new RecipeService($this->recipeRepository,$this->recipeModelMapper,$this->userRepository))
                    ->getPublicRecipesByCooker(1);

       $this->assertEquals(RecipeModel::class,get_class($actual));
       $this->assertCount(2,$actual->getRecipes());
       $this->assertEquals("test-test",$actual->getRecipes()[1]->getTitle());
       $this->assertEquals(11,$actual->getRecipes()[0]->getRating()+$actual->getRecipes()[1]->getRating());
    }

    public function testGetRecipesShortFrom()
    {
         $recipe1 = (new Recipe())->setTitle("test-test");
         $recipe2 = (new Recipe())->setTitle("test-test");
         $recipes = [$recipe1,$recipe2];

          $this->recipeRepository->expects($this->once())
              ->method("get10Recipe")
              ->willReturn($recipes);

        $this->recipeModelMapper->expects($this->exactly(2))
            ->method("mapRecipeShortForm")
            ->withConsecutive([$recipe1],[$recipe2])
            ->willReturn((new RecipeItem())->setTitle("test-test")->setRating(5.5));

        $actual = (new RecipeService($this->recipeRepository,$this->recipeModelMapper,$this->userRepository))
                  ->getRecipesShortFrom();

        $this->assertEquals(RecipeModel::class,get_class($actual));
        $this->assertCount(2,$actual->getRecipes());
    }

    public function testGetRecipeByIdExtendForm()
    {
        $recipe = (new Recipe())->setTitle("test-test");

        $this->recipeRepository->expects($this->once())
            ->method("getPublicRecipeById")
            ->with(3)
            ->willReturn($recipe);

        $this->recipeModelMapper->expects($this->once())
            ->method("mapRecipeExtendForm")
            ->with($recipe)
            ->willReturn((new RecipeExtensive())->setTitle("tick-tack")->setRating(5)->setSteps(["test","test"]));

        $actual = (new RecipeService($this->recipeRepository,$this->recipeModelMapper,$this->userRepository))
                    ->getRecipeByIdExtendForm(3);

        $this->assertEquals(RecipeExtensive::class,get_class($actual));
        $this->assertEquals("tick-tack",$actual->getTitle());
        $this->assertCount(2,$actual->getSteps());
    }

    public function testGetRecipesByCuisineIdShortForm()
    {
        $recipe1 = (new Recipe())->setTitle("test-test");
        $recipe2 = (new Recipe())->setTitle("test-test");
        $recipes = [$recipe1,$recipe2];

        $this->recipeRepository->expects($this->once())
            ->method("getRecipesByCuisineId")
            ->with(5)
            ->willReturn($recipes);

        $this->recipeModelMapper->expects($this->exactly(2))
            ->method("mapRecipeShortForm")
            ->withConsecutive([$recipe1],[$recipe2])
            ->willReturn((new RecipeItem())->setTitle("test-test")->setRating(5.5));

        $actual = (new RecipeService($this->recipeRepository,$this->recipeModelMapper,$this->userRepository))
            ->getRecipesByCuisineIdShortForm(5);
        $this->assertEquals(RecipeModel::class,get_class($actual));
        $this->assertCount(2,$actual->getRecipes());
    }
}
