<?php

namespace App\Utils\Mapper;

use App\Entity\Recipe as RecipeEntity;
use App\Entity\User;
use App\Model\RecipeExtensive;
use App\Model\RecipeItem;

class RecipeModelMapper extends  Mapper
{


    public function mapRecipeShortForm(RecipeEntity $recipe_e) : RecipeItem
    {
        /**@var User $user */
        $user = $recipe_e->getUser();
        $reviewCount = $this->reviewRepository->getCountByRecipeId($recipe_e->getId());
        if ($reviewCount === null)
        {
            $reviewCount = 0;
        }
        $rating = null;
        if($reviewCount !== 0) {
            $rating = round($this->reviewRepository->getRating($recipe_e->getId()) / $reviewCount, 1);
        }
        return  (new RecipeItem())
            ->setId($recipe_e->getId())
            ->setTitle($recipe_e->getTitle())
            ->setImage($recipe_e->getImage())
            ->setSlug($recipe_e->getSlug())
            ->setCooker($this->getCookerFullNameByCookerId($user->getId()))
            ->setCuisine($recipe_e->getCuisine() ? ($recipe_e->getCuisine())->getTitle(): null)
            ->setRating($rating)
            ->setPublicDate($recipe_e->getPublicationDate()?->getTimestamp())
            ->setReviewCount($reviewCount);
    }


    public function mapRecipeExtendForm(RecipeEntity $recipe_e) : RecipeExtensive
    {
        /**@var User $user */
        $user = $recipe_e->getUser();
        $reviewCount = $this->reviewRepository->getCountByRecipeId($recipe_e->getId());
        $rating = null;
        if($reviewCount !== 0) {
            $rating = round($this->reviewRepository->getRating($recipe_e->getId()) / $reviewCount, 1);
        }
        return  (new RecipeExtensive())
            ->setId($recipe_e->getId())
            ->setTitle($recipe_e->getTitle())
            ->setImage($recipe_e->getImage())
            ->setSlug($recipe_e->getSlug())
            ->setCooker($this->getCookerFullNameByCookerId($user->getId())) //
            ->setCuisine($recipe_e->getCuisine() ?($recipe_e->getCuisine())->getTitle(): null)
            ->setRating($rating)
            ->setReviewCount($reviewCount)
            ->setImages($recipe_e->getImages())
            ->setDescription($recipe_e->getDescription())
            ->setPublicationDate($recipe_e->getPublicationDate()->getTimestamp())
            ->setIngredients($recipe_e->getIngredients())
            ->setSteps($recipe_e->getSteps());
    }
}
