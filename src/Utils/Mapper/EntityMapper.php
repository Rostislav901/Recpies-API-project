<?php

namespace App\Utils\Mapper;

use App\Entity\Recipe;
use App\Entity\Review;
use App\Entity\User;
use App\Model\RecipeManager\RequestRecipeModel;
use App\Model\Registration;
use App\Model\ReviewItem;
use App\Model\SubmitReview;
use App\Repository\CuisineRepository;
use App\Repository\RecipeRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class EntityMapper extends Mapper
{

    public function mapRecipe(Recipe &$recipe, RequestRecipeModel $data) : Recipe
    {
        $recipe
            ->setTitle($data->getTitle())
            ->setDescription($data->getDescription())
            ->setSlug($data->getSlug())
            ->setImage($data->getMainImage())
            ->setIngredients($data->getIngredients())
            ->setUser($this->security->getUser())
            ->setSteps($data->getSteps())
            ->setImages($data->getImages());
        if($data->getCuisineId() !== null)
        {
            $recipe->setCuisine($this->cuisineRepository->find($data->getCuisineId()));
        }
        return  $recipe;
    }

    public function mapReview(Review &$review,SubmitReview $submitReview) : Review
    {
        return      $review
            ->setRating($submitReview->getRating())
            ->setText($submitReview->getText());


    }

    public function mapUserEntity(Registration $request) : UserInterface
    {
        $user = (new User())
            ->setEmail($request->getEmail())
            ->setFirstName($request->getFirstName())
            ->setLastName($request->getLastName())
            ->setCountry($request->getCountry())
            ->setRoles([Registration::ROLE])
            ->setAboutMe($request->getAboutMe());
        $user->setPassword($this->hasher->hashPassword($user,$request->getPassword()));


        return $user;
    }

}
