<?php

namespace App\Utils\Mapper;

use App\Entity\User;
use App\Model\PrivateCooker;
use App\Model\PublicCooker;
use Symfony\Component\Security\Core\User\UserInterface;

class CookerModelMapper extends Mapper
{

    public function mapCooker(int $id) : PublicCooker
    {
        $cooker_e = $this->userRepository->getById($id);

        $publicRecipes = $this->recipeRepository->getPublicRecipesByUser($cooker_e);
        $recipeCount = count($publicRecipes);
        return (new PublicCooker())
            ->setName($this->getCookerFullNameByCookerId($id))
            ->setCookerInfo($cooker_e->getAboutMe())
            ->setCountry($cooker_e->getCountry())
            ->setRegistrationDate($cooker_e->getCreatedAt()->getTimestamp())
            ->setRecipeCount($recipeCount)
            ->setRecipeAverageRating($recipeCount !== 0 ? $this->methodHelper->calculateAverageRating($publicRecipes):0);

    }

    public function mapPrivateCooker (UserInterface $user) : PrivateCooker
    {
        $recipes = $this->recipeRepository->getPublicRecipesByUser($user);
        $averageRating =  $this->methodHelper->calculateAverageRating($recipes);

        /** @var User $user */
        return  (new PrivateCooker())
            ->setFullName($this->getCookerFullNameByCookerId($user->getId()))
            ->setEmail($user->getEmail())
            ->setAboutMe($user->getAboutMe())
            ->setCountry($user->getCountry())
            ->setRegistrationDate($user->getCreatedAt()->getTimestamp())
            ->setCountRecipes($this->recipeRepository->getCountByUser($user))
            ->setCountPrivateRecipes($this->recipeRepository->getPrivateCountByUser($user))
            ->setCountPublicRecipes($this->recipeRepository->getPublicCountByUser($user))
            ->setAverageRecipesRating($averageRating)
            ->setTotalReviewCount($this->methodHelper->getTotalReviewCount($recipes));
    }
}
