<?php

namespace App\Service\RecipeMangerServices;

use App\Repository\RecipeRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\SecurityBundle\Security;

class DeleteRecipeService
{
    public function __construct(
                                private Security $security,
                                private ReviewRepository $reviewRepository,
                                private RecipeRepository $recipeRepository)
    {
    }


    public function deleteRecipe(int $id) : void
    {
         $recipe = $this->recipeRepository->findByUserId($id,$this->security->getUser());


         $this->recipeRepository->deleteRecipe($recipe);
    }

    public function deleteReview(int $id) : void
    {
         $user = $this->security->getUser();
         $review = $this->reviewRepository->getUserReviewById($id,$user);

         $this->reviewRepository->deleteReview($review);

    }
}
