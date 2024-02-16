<?php

namespace App\Tests\Repository;

use App\Entity\Review;
use App\Entity\User;
use App\Entity\Recipe;
use App\Exceptions\ChangeReviewAccessException;
use App\Exceptions\RecipeNotFoundException;
use App\Exceptions\ReviewNotFoundException;
use App\Repository\RecipeRepository;
use App\Repository\ReviewRepository;
use App\Tests\AbstractRepositoryTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class ReviewRepositoryTest extends AbstractRepositoryTestCase
{
    protected ReviewRepository $reviewRepository;
    protected RecipeRepository $recipeRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reviewRepository = $this->getRepositoryForEntity(Review::class);
        $this->recipeRepository = $this->getRepositoryForEntity(Recipe::class);
    }

    public function testGetCountByRecipeId()
    {
          $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")->setPassword("root")->setRoles(["role"]);

          $recipe = (new Recipe())->setTitle("t")->setSlug("s")->setImage("i")->setIngredients(["i"])->setSteps(["s"]);

//          $recipeTest = (new Recipe())->setTitle("Test")->setSlug("s")->setImage("i")->setIngredients(["i"])->setSteps(["s"]);

          $this->entityManager->persist($user);
          $this->entityManager->persist($recipe);

//          $this->entityManager->persist($recipeTest);

           for ($i = 0; $i < 5; $i++)
           {
               $review = $this->createReview("text",$user,$recipe,10);
               $this->entityManager->persist($review);
           }
           $this->entityManager->flush();

           $this->assertEquals(5,$this->reviewRepository->getCountByRecipeId($recipe->getId()));
//           $this->assertEquals(0,$this->reviewRepository->getCountByRecipeId($recipeTest->getId()));

    }

    public function testGetRating()
    {
        $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")->setPassword("root")->setRoles(["role"]);

        $recipe = (new Recipe())->setTitle("t")->setSlug("s")->setImage("i")->setIngredients(["i"])->setSteps(["s"]);

        $this->entityManager->persist($user);
        $this->entityManager->persist($recipe);

        for ($i = 0; $i < 5; $i++)
        {
            $review = $this->createReview("text",$user,$recipe,10);
            $this->entityManager->persist($review);
        }
        $this->entityManager->flush();

        $this->assertEquals(50,$this->reviewRepository->getRating($recipe->getId()));
    }

    public function testGetAllByRecipeIdRecipeNotPublic()
    {
        $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")->setPassword("root")->setRoles(["role"]);

        $recipe = (new Recipe())->setTitle("t")->setSlug("s")->setImage("i")->setIngredients(["i"])->setSteps(["s"]);

        $this->entityManager->persist($user);
        $this->entityManager->persist($recipe);
        for ($i = 0; $i < 5; $i++)
        {
            $review = $this->createReview("text",$user,$recipe,10);
            $this->entityManager->persist($review);
        }
        $this->entityManager->flush();

        $recipeUnPublish = $this->recipeRepository->find($recipe->getId());
        $recipeUnPublish->setPublicationDate(null);

        $this->entityManager->flush();

        $this->expectException(RecipeNotFoundException::class);
        $this->reviewRepository->getAllByRecipeId($recipe->getId());
    }
    public function testGetAllByRecipeIdReviewNotFoundException()
    {
        $recipe = (new Recipe())->setTitle("t")->setSlug("s")->setImage("i")->setIngredients(["i"])->setSteps(["s"]);
        $this->entityManager->persist($recipe);
        $this->entityManager->flush();
        $this->expectException(ReviewNotFoundException::class);

        $this->reviewRepository->getAllByRecipeId($recipe->getId());

    }
    public function testGetAllByRecipeId()
    {
        $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")->setPassword("root")->setRoles(["role"]);

        $recipe = (new Recipe())->setTitle("t")->setSlug("s")->setImage("i")->setIngredients(["i"])->setSteps(["s"]);

        $this->entityManager->persist($user);
        $this->entityManager->persist($recipe);

        for ($i = 0; $i < 2; $i++)
        {
            $review = $this->createReview("text",$user,$recipe,10);
            $this->entityManager->persist($review);


        }
        $this->entityManager->flush();

        $this->assertEquals(2,count($this->reviewRepository->getAllByRecipeId($recipe->getId())));

        $this->assertEquals([["text",10],["text",10]],array_map(fn(Review $review) => [$review->getText(),$review->getRating()],
                                                         $this->reviewRepository->getAllByRecipeId($recipe->getId())));

    }

    public function testExistByIdTrue()
    {
         $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")->setPassword("root")->setRoles(["role"]);

         $recipe = (new Recipe())->setTitle("t")->setSlug("s")->setImage("i")->setIngredients(["i"])->setSteps(["s"]);

         $this->entityManager->persist($user);
         $this->entityManager->persist($recipe);

         $review = $this->createReview("review",$user,$recipe,20);
         $this->entityManager->persist($review);

         $this->entityManager->flush();

         $this->assertTrue($this->reviewRepository->existById($review->getId()));
    }

    public function testExistByIdFalse()
    {
        $this->assertFalse($this->reviewRepository->existById(3));
    }

    public function testGetUserReviewByIdReviewNotFoundException()
    {

         $this->expectException(ReviewNotFoundException::class);
         $this->reviewRepository->getUserReviewById(3,new User());

    }

    public function testGetUserReviewByIdChangeReviewAccessException()
    {
        $user1 = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")->setPassword("root")->setRoles(["role"]);
        $user2 = (new User())->setFirstName("f")->setLastName("l")->setEmail("email2")->setPassword("root")->setRoles(["role"]);
        $recipe = (new Recipe())->setTitle("t")->setSlug("s")->setImage("i")->setIngredients(["i"])->setSteps(["s"]);

        $this->entityManager->persist($user1);
        $this->entityManager->persist($user2);
        $this->entityManager->persist($recipe);

        $review = $this->createReview("review",$user1,$recipe,20);
        $this->entityManager->persist($review);

        $this->entityManager->flush();

        $this->expectException(ChangeReviewAccessException::class);

        $this->reviewRepository->getUserReviewById($review->getId(),$user2);


    }

    public function testGetUserReviewById()
    {
        $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")->setPassword("root")->setRoles(["role"]);

        $recipe = (new Recipe())->setTitle("t")->setSlug("s")->setImage("i")->setIngredients(["i"])->setSteps(["s"]);

        $this->entityManager->persist($user);
        $this->entityManager->persist($recipe);

        $review = $this->createReview("review",$user,$recipe,20);
        $this->entityManager->persist($review);

        $this->entityManager->flush();
        $expected = (new Review())
                            ->setId($review->getId())
                            ->setText("review")
                            ->setUser($user)
                            ->setRecipe($recipe)
                            ->setRating(20)
                            ->setReviewPublicDate($review->getReviewPublicDate());

        $this->assertEquals( $expected,
                             $this->reviewRepository->getUserReviewById($review->getId(),$user));
    }

    private function createReview(string $text,UserInterface $user,Recipe $recipe,int $rating) : Review
    {
            return  (new  Review())
                            ->setUser($user)
                            ->setRecipe($recipe)
                            ->setText($text)
                            ->setRating($rating);

    }
}
