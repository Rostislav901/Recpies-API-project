<?php

namespace App\Service\RecipeMangerServices;
use App\Model\PrivateCooker;
use App\Model\Recipe;
use App\Repository\RecipeRepository;
use App\Utils\Mapper\CookerModelMapper;
use App\Utils\Mapper\RecipeModelMapper;
use Symfony\Bundle\SecurityBundle\Security;
class GetRecipeService
{
      public function __construct(private Security $security,
                                  private RecipeRepository $recipeRepository,
                                  private RecipeModelMapper $recipeModelMapper,
                                  private CookerModelMapper $cookerModelMapper)
      {
      }




      public function getRecipes() : Recipe
      {
          $user = $this->security->getUser();

          return (new Recipe())->setRecipes(array_map([$this->recipeModelMapper,"mapRecipeShortForm"],$this->recipeRepository->getRecipesByUser($user)));
      }


      public function getCookerInfo() : PrivateCooker
      {
           $user = $this->security->getUser();

           return $this->cookerModelMapper->mapPrivateCooker($user);
      }



}
