<?php

namespace App\Tests\Utils;

use App\Repository\CuisineRepository;
use App\Repository\RecipeRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use App\Utils\MethodHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AbstractMapperTestCase extends TestCase
{
    protected ReviewRepository $reviewRepository;
    protected UserRepository $userRepository;
    protected RecipeRepository $recipeRepository;
    protected CuisineRepository $cuisineRepository;
    protected MethodHelper $methodHelper;
    protected Security $security;
    protected UserPasswordHasherInterface $hasher;


    protected function setUp(): void
    {
        parent::setUp();

        $this->reviewRepository = $this->createMock(ReviewRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->recipeRepository = $this->createMock(RecipeRepository::class);
        $this->cuisineRepository = $this->createMock(CuisineRepository::class);
        $this->methodHelper = $this->createMock(MethodHelper::class);
        $this->security = $this->createMock(Security::class);
        $this->hasher = $this->createMock(UserPasswordHasherInterface::class);


    }
}
