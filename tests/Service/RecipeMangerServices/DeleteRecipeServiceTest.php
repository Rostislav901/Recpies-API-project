<?php

namespace App\Tests\Service\RecipeMangerServices;

use App\Entity\Recipe;
use App\Entity\User;
use App\Entity\Review;
use App\Repository\RecipeRepository;
use App\Repository\ReviewRepository;
use App\Service\RecipeMangerServices\DeleteRecipeService;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;

class DeleteRecipeServiceTest extends TestCase
{

    private Security $security;
    private ReviewRepository $reviewRepository;
    private RecipeRepository $recipeRepository;
    private User $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = new User();
        $this->security = $this->createMock(Security::class);
        $this->reviewRepository = $this->createMock(ReviewRepository::class);
        $this->recipeRepository = $this->createMock(RecipeRepository::class);
        $this->security->expects($this->once())
            ->method("getUser")
            ->willReturn($this->user);
    }

    public function testDeleteReview()
    {
         $returnReview = new Review();

         $this->reviewRepository->expects($this->once())
             ->method("getUserReviewById")
             ->with(5,$this->user)
             ->willReturn($returnReview);

         $this->reviewRepository->expects($this->once())
             ->method("deleteReview")
             ->with($returnReview);

        (new DeleteRecipeService($this->security,$this->reviewRepository,$this->recipeRepository))->deleteReview(5);
    }

    public function testDeleteRecipe()
    {
         $recipe = new Recipe();

         $this->recipeRepository->expects($this->once())
             ->method("findByUserId")
             ->with(3,$this->user)
             ->willReturn($recipe);

         $this->recipeRepository->expects($this->once())
             ->method("deleteRecipe")
             ->with($recipe);

        (new DeleteRecipeService($this->security,$this->reviewRepository,$this->recipeRepository))->deleteRecipe(3);

    }
}
