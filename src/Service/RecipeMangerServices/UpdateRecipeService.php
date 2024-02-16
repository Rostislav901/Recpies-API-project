<?php

namespace App\Service\RecipeMangerServices;

use App\Exceptions\RecipeAlreadyPublishException;
use App\Exceptions\RecipeAlreadyUnPublishException;
use App\Model\RecipeManager\RequestRecipeModel;
use App\Model\SubmitReview;
use App\Repository\RecipeRepository;
use App\Repository\ReviewRepository;
use App\Utils\Mapper\EntityMapper;
use DateTimeImmutable;
use Symfony\Bundle\SecurityBundle\Security;

class UpdateRecipeService
{
    public function __construct(private Security $security,
                                private RecipeRepository $recipeRepository,
                                private EntityMapper $entityMapper,
                                private ReviewRepository $reviewRepository,
                                )
    {}


    public function publish (int $id) : void
    {
         $recipe = $this->recipeRepository->findByUserId($id,$this->security->getUser());
         if(!($recipe->getPublicationDate() === null))
         {
             throw new RecipeAlreadyPublishException();

         }
         $recipe->setPublicationDate(new DateTimeImmutable());

         $this->recipeRepository->saveRecipe($recipe);
    }

    public function  unpublish(int $id) : void
    {
        $recipe = $this->recipeRepository->findByUserId($id,$this->security->getUser());

        if ($recipe->getPublicationDate() === null)
        {
            throw new RecipeAlreadyUnPublishException();
        }
        $recipe->setPublicationDate(null);

        $this->recipeRepository->saveRecipe($recipe);
    }

    public function  updateRecipe($id,RequestRecipeModel $recipeModel) : void
    {
             $recipe = $this->recipeRepository->findByUserId($id,$this->security->getUser());
             $updateRecipe = $this->entityMapper->mapRecipe($recipe,$recipeModel);


             $this->recipeRepository->saveRecipe($updateRecipe);

    }

    public function updateReview(int $id,SubmitReview $submitReview) : void
    {
         $review = $this->reviewRepository->getUserReviewById($id,$this->security->getUser());
         $review = $this->entityMapper->mapReview($review,$submitReview);
         $this->reviewRepository->saveReview($review);
    }
}
