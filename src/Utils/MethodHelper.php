<?php

namespace App\Utils;

use App\Entity\Recipe;
use App\Repository\ReviewRepository;

class MethodHelper
{

    public function __construct(private ReviewRepository $reviewRepository)
    {
    }

    /**
     * @param Recipe[] $recipes
     */
    public function calculateAverageRating(array $recipes) : float
    {
        $totalRating = 0;
        $count = count($recipes);
        foreach ($recipes as $recipe)
        {
            $recipeId  = $recipe->getId();
            $reviewCount = $this->reviewRepository->getCountByRecipeId($recipeId);

            $avgRating = 0;
            if($reviewCount !== 0 )
            {
                $rating  = $this->reviewRepository->getRating($recipeId);
                $avgRating  = round($rating/$reviewCount,1);
            }
            else
            {
                $count--;
            }
            $totalRating += $avgRating;
        }
        return $count > 0 ? round($totalRating/$count,1) : 0;
    }

    /**
     * @param Recipe[]$recipes
     * @return int
     */
    public function getTotalReviewCount(array $recipes) : int
    {
          $count = 0;
          foreach ($recipes as $recipe)
          {
              $count +=  $this->reviewRepository->getCountByRecipeId($recipe->getId());
          }
          return  $count;
    }


}
