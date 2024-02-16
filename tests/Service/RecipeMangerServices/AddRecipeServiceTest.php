<?php

namespace App\Tests\Service\RecipeMangerServices;

use App\Entity\Recipe;
use App\Entity\Review;
use App\Entity\User;
use App\Exceptions\SubmitReviewAccessException;
use App\Model\RecipeManager\RequestRecipeModel;
use App\Model\SubmitReview;
use App\Repository\RecipeRepository;
use App\Repository\ReviewRepository;
use App\Service\RecipeMangerServices\AddRecipeService;
use App\Utils\Mapper\EntityMapper;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;

class AddRecipeServiceTest extends TestCase
{

    private ReviewRepository $reviewRepository;
    private Security $security;
    private RecipeRepository $recipeRepository;
    private EntityMapper $entityMapper;


    protected function setUp(): void
    {
        parent::setUp();
        $this->reviewRepository = $this->createMock(ReviewRepository::class);
        $this->security = $this->createMock(Security::class);
        $this->recipeRepository = $this->createMock(RecipeRepository::class);
        $this->entityMapper = $this->createMock(EntityMapper::class);
    }

    public function testAddRecipe()
    {
             $data = (new RequestRecipeModel())->setTitle("text")->setMainImage("mainImageTest.png");
             $updateRecipe = (new Recipe())->setTitle($data->getTitle())->setImage($data->getMainImage());
             $this->entityMapper->expects($this->once())
                    ->method("mapRecipe")
                    ->with(new Recipe(),$data)
                    ->willReturn($updateRecipe);

             $this->recipeRepository->expects($this->once())
                    ->method("saveRecipe")
                    ->with($updateRecipe);

             $service  = new AddRecipeService($this->reviewRepository,$this->security,$this->recipeRepository,$this->entityMapper);
             $service->addRecipe($data);
    }

    public function testAddReviewThrowException()
    {
          $user = new User();
          $recipe = (new Recipe())->setUser($user);

          $this->recipeRepository->expects($this->once())
              ->method("getPublicRecipeById")
              ->with(3)
              ->willReturn($recipe);

          $this->security->expects($this->once())
              ->method("getUser")
              ->willReturn($user);


          $this->expectException(SubmitReviewAccessException::class);
        (new AddRecipeService($this->reviewRepository,$this->security,$this->recipeRepository,$this->entityMapper))
                ->addReview(3,new SubmitReview());
    }

    public function testAddReview()
    {
        $user = new User();
        $recipe = (new Recipe())->setUser($user);
        $submitReview = (new SubmitReview())->setText("test");
        $creator = new User();
        $resultReview = new Review();
        $this->recipeRepository->expects($this->once())
            ->method("getPublicRecipeById")
            ->with(3)
            ->willReturn($recipe);

        $this->security->expects($this->once())
            ->method("getUser")
            ->willReturn($creator);

        $this->entityMapper->expects($this->once())
            ->method("mapReview")
            ->with(new Review(),$submitReview)
            ->willReturn($resultReview->setText($submitReview->getText()));

        $this->reviewRepository->expects($this->once())
            ->method("saveReview")
            ->with($resultReview->setUser($creator)->setRecipe($recipe));


        (new AddRecipeService($this->reviewRepository,$this->security,$this->recipeRepository,$this->entityMapper))
            ->addReview(3,$submitReview);
    }
}
