<?php

namespace App\Utils\Mapper;

use App\Repository\CuisineRepository;
use App\Repository\RecipeRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use App\Utils\MethodHelper;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class Mapper
{
        public function __construct(public ?ReviewRepository $reviewRepository = null,
                                    public ?UserRepository $userRepository = null,
                                    public ?RecipeRepository $recipeRepository = null,
                                    public ?CuisineRepository $cuisineRepository = null,
                                    public ?MethodHelper $methodHelper = null,
                                    public ?Security $security = null,
                                    public ?UserPasswordHasherInterface $hasher = null)
        {}


     public function getCookerFullNameByCookerId(int $id) : string
     {
         return  $this->userRepository->getFullNameById($id);
     }

}
