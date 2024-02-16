<?php

namespace App\Tests\Service\RecipeMangerServices;

use App\Entity\Recipe;
use App\Entity\Review;
use App\Entity\User;
use App\Exceptions\RecipeAlreadyPublishException;
use App\Exceptions\RecipeAlreadyUnPublishException;
use App\Model\RecipeManager\RequestRecipeModel;
use App\Model\SubmitReview;
use App\Repository\RecipeRepository;
use App\Repository\ReviewRepository;
use App\Service\RecipeMangerServices\UpdateRecipeService;
use App\Utils\Mapper\EntityMapper;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;

class UpdateRecipeServiceTest extends TestCase
{
    private Security $security;
    private RecipeRepository $recipeRepository;
    private EntityMapper $entityMapper;
    private ReviewRepository $reviewRepository;
    private User $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->security = $this->createMock(Security::class);
        $this->recipeRepository = $this->createMock(RecipeRepository::class);
        $this->entityMapper = $this->createMock(EntityMapper::class);
        $this->reviewRepository = $this->createMock(ReviewRepository::class);
        $this->user = new User();

        $this->security->expects($this->once())
            ->method("getUser")
            ->willReturn($this->user);
    }


    public function testPublishThrowException()
    {
         $this->recipeRepository->expects($this->once())
             ->method("findByUserId")
             ->with(3,$this->user)
             ->willReturn((new Recipe())->setPublicationDate(new DateTimeImmutable()));


         $this->expectException(RecipeAlreadyPublishException::class);


        (new UpdateRecipeService($this->security,$this->recipeRepository,$this->entityMapper,$this->reviewRepository))
            ->publish(3);
    }

    public function testPublish()
    {
        $recipe = (new Recipe())->setPublicationDate(null);
        $this->recipeRepository->expects($this->once())
            ->method("findByUserId")
            ->with(3,$this->user)
            ->willReturn($recipe);

        $this->recipeRepository->expects($this->once())
            ->method("saveRecipe")
            ->with($recipe);

        (new UpdateRecipeService($this->security,$this->recipeRepository,$this->entityMapper,$this->reviewRepository))
            ->publish(3);
        $this->assertNotNull($recipe->getPublicationDate());
    }

    public function testUnpublishThrowException()
    {
        $this->recipeRepository->expects($this->once())
            ->method("findByUserId")
            ->with(3,$this->user)
            ->willReturn((new Recipe())->setPublicationDate(null));

        $this->expectException(RecipeAlreadyUnPublishException::class);

        (new UpdateRecipeService($this->security,$this->recipeRepository,$this->entityMapper,$this->reviewRepository))
            ->unpublish(3);
    }

    public function testUnpublish()
    {
        $recipe = (new Recipe())->setPublicationDate(new DateTimeImmutable());
        $this->recipeRepository->expects($this->once())
            ->method("findByUserId")
            ->with(3,$this->user)
            ->willReturn($recipe);

        $this->recipeRepository->expects($this->once())
            ->method("saveRecipe")
            ->with($recipe);

        (new UpdateRecipeService($this->security,$this->recipeRepository,$this->entityMapper,$this->reviewRepository))
            ->unpublish(3);
        $this->assertNull($recipe->getPublicationDate());
    }

    public function testUpdateRecipe()
    {
        $recipe = (new Recipe())->setPublicationDate(new DateTimeImmutable());
        $updateRecipe = (new Recipe())->setTitle("test");
        $recipeModel = new RequestRecipeModel();
        $this->recipeRepository->expects($this->once())
            ->method("findByUserId")
            ->with(3,$this->user)
            ->willReturn($recipe);

        $this->entityMapper->expects($this->once())
            ->method("mapRecipe")
            ->with($recipe,$recipeModel)
            ->willReturn($updateRecipe);

        $this->recipeRepository->expects($this->once())
            ->method("saveRecipe")
            ->with($updateRecipe);
        (new UpdateRecipeService($this->security,$this->recipeRepository,$this->entityMapper,$this->reviewRepository))
            ->updateRecipe(3,$recipeModel);
    }


    public function testUpdateReview()
    {
        $submitReview = new SubmitReview();
        $review = new Review();
        $res = new Review();
        $this->reviewRepository->expects($this->once())
            ->method("getUserReviewById")
            ->with(5,$this->user)
            ->willReturn($review);

        $this->entityMapper->expects($this->once())
            ->method("mapReview")
            ->with($review,$submitReview)
            ->willReturn($res);

        $this->reviewRepository->expects($this->once())
            ->method("saveReview")
            ->with($res);

        (new UpdateRecipeService($this->security,$this->recipeRepository,$this->entityMapper,$this->reviewRepository))
                ->updateReview(5,$submitReview);

    }
}
