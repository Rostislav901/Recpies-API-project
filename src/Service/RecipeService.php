<?php

namespace App\Service;

use App\Exceptions\RecipeNotFoundException;
use App\Model\Recipe;
use App\Model\RecipeExtensive;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use App\Utils\Mapper\RecipeModelMapper;
use App\Utils\ModelMapper;

class RecipeService
{

    public function __construct(
                                private RecipeRepository  $recipeRepository,
                                private RecipeModelMapper $recipeModelMapper,
                                private UserRepository $userRepository,
                                ){}

    public function getRecipeByIdExtendForm(int $id) : RecipeExtensive
    {
        $recipe_e = $this->recipeRepository->getPublicRecipeById($id);
        return $this->recipeModelMapper->mapRecipeExtendForm($recipe_e);
    }


    public function getRecipesByCuisineIdShortForm( int $id) : Recipe
    {
        $recipes =  $this->recipeRepository->getRecipesByCuisineId($id);
        return  (new Recipe())->setRecipes(array_map([$this->recipeModelMapper,"mapRecipeShortForm"],$recipes));

    }

    public function getRecipesShortFrom() : Recipe
    {
        $recipes = $this->recipeRepository->get10Recipe();
        return (new Recipe())->setRecipes(array_map([$this->recipeModelMapper,"mapRecipeShortForm"],$recipes));
    }

    public function getPublicRecipesByCooker(int $id) : Recipe
    {
         $user = $this->userRepository->getById($id);
         $recipes = $this->recipeRepository->getPublicRecipesByUser($user);
         if ($recipes === [])
         {
             throw new RecipeNotFoundException();
         }

         return   (new Recipe())->setRecipes(array_map([$this->recipeModelMapper,"mapRecipeShortForm"],$recipes));
    }


}
