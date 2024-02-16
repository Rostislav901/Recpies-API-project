<?php

namespace App\Service\RecipeMangerServices;

use App\Entity\Recipe;
use App\Entity\Review;
use App\Exceptions\SubmitReviewAccessException;
use App\Model\RecipeManager\RequestRecipeModel;
use App\Model\SubmitReview;
use App\Repository\RecipeRepository;
use App\Repository\ReviewRepository;
use App\Utils\Mapper\EntityMapper;
use Symfony\Bundle\SecurityBundle\Security;

class AddRecipeService
{
      public function __construct(private ReviewRepository $reviewRepository,
                                  private Security $security,
                                  private RecipeRepository $recipeRepository,
                                  private EntityMapper $entityMapper,
                                  )
      {
      }

      public function addRecipe(RequestRecipeModel $data) : void
      {
          $recipe = new  Recipe();
          $recipe =  $this->entityMapper->mapRecipe($recipe,$data);

          $this->recipeRepository->saveRecipe($recipe);

      }

      public function addReview(int $id,SubmitReview $submitReview) : void
      {
          $recipe =  $this->recipeRepository->getPublicRecipeById($id);
          $recipeCooker = $recipe->getUser();
          $creator = $this->security->getUser();
          $review = new Review();
          if($creator === $recipeCooker)
          {
              throw new SubmitReviewAccessException();
          }
          $review = $this->entityMapper->mapReview($review,$submitReview)
                ->setRecipe($recipe)
                ->setUser($creator);

          $this->reviewRepository->saveReview($review);

      }



}
