<?php

namespace App\Tests\Repository;

use App\Entity\Cuisine;
use App\Entity\Recipe;
use App\Entity\User;
use App\Exceptions\CuisineNotFoundException;
use App\Exceptions\RecipeNotFoundException;
use App\Repository\RecipeRepository;
use App\Tests\AbstractRepositoryTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class RecipeRepositoryTest extends AbstractRepositoryTestCase
{

    protected RecipeRepository $recipeRepository;


    protected function setUp(): void
    {
        parent::setUp();
        $this->recipeRepository = $this->getRepositoryForEntity(Recipe::class);
    }

    public function testGetRecipesByCuisineIdCuisineNotFoundException()
    {
        $this->expectException(CuisineNotFoundException::class);
        $this->recipeRepository->getRecipesByCuisineId(3);
    }

    public function testGetRecipesByCuisineIdRecipeNotFoundException()
    {
        $cuisine = (new Cuisine())->setSlug("slug")->setTitle("title");


        $this->entityManager->persist($cuisine);
        $this->entityManager->flush();

        $this->expectException(RecipeNotFoundException::class);

        $this->recipeRepository->getRecipesByCuisineId($cuisine->getId());

    }

    public function testGetRecipesByCuisineId ()
    {
        $cuisine = (new Cuisine())->setSlug("slug")->setTitle("title");
        $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")->setPassword("root")->setRoles(["role"]);
        $this->entityManager->persist($cuisine);
        $this->entityManager->persist($user);
        for ( $i = 0; $i < 2; $i++ )
        {
                $recipe = $this->createRecipe("t","s","i",["ing"],["ste"],$user,$cuisine);
                $this->entityManager->persist($recipe);

        }
        $recipeTest = $this->createRecipe("t","s","i",["ing"],["ste"],$user,$cuisine);
        $this->entityManager->persist($recipeTest);
        $this->entityManager->flush();

        $unpublishRecipe =  $this->recipeRepository->find($recipeTest->getId());
        $unpublishRecipe->setPublicationDate(null);
        $this->entityManager->persist($unpublishRecipe);

        $this->entityManager->flush();

        $this->assertCount(2,$this->recipeRepository->getRecipesByCuisineId($cuisine->getId()));

    }

    public function testGetPublicRecipeByIdRecipeNotFoundException()
    {
        $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")
                            ->setPassword("root")->setRoles(["role"]);

        $this->entityManager->persist($user);

        $recipe = $this->createRecipe("t","s","i",["ing"],["ste"],$user);
        $this->entityManager->persist($recipe);

        $this->entityManager->flush();
        $unpublishRecipe =  $this->recipeRepository->find($recipe->getId());
        $unpublishRecipe->setPublicationDate(null);
        $this->entityManager->persist($unpublishRecipe);

        $this->entityManager->flush();

        $this->expectException(RecipeNotFoundException::class);

        $this->recipeRepository->getPublicRecipeById($recipe->getId());


    }

    public function testGetPublicRecipeById()
    {
        $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")
            ->setPassword("root")->setRoles(["role"]);

        $this->entityManager->persist($user);

        $recipe = $this->createRecipe("t","s","i",["ing"],["ste"],$user);
        $this->entityManager->persist($recipe);

        $this->entityManager->flush();
        $actual = $this->recipeRepository->getPublicRecipeById($recipe->getId());
        $this->assertEquals(["t","s",$recipe->getPublicationDate()],[$actual->getTitle(),$actual->getSlug(),$actual->getPublicationDate()]);
    }

    public function testGetRecipesByUserRecipeNotFoundException()
    {
        $this->expectException(RecipeNotFoundException::class);

        $this->recipeRepository->getRecipesByUser(new User());
    }

    public function testGetRecipesByUser()
    {
        $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")
            ->setPassword("root")->setRoles(["role"]);

        $this->entityManager->persist($user);

        $recipe = $this->createRecipe("t","s","i",["ing"],["ste"],$user);
        $this->entityManager->persist($recipe);

        $recipe2 = $this->createRecipe("t2","s2","i2",["ing2"],["ste2"],$user);
        $this->entityManager->persist($recipe2);

        $this->entityManager->flush();

        $recipe2->setPublicationDate(null);

        $this->entityManager->persist($recipe2);
        $this->entityManager->flush();

        $actual = $this->recipeRepository->getRecipesByUser($user);
        $this->assertCount(2,$actual);
        $this->assertEquals(["t","t2"],[$actual[0]->getTitle(),$actual[1]->getTitle()]);
    }

    public function testGet10RecipeRecipeNotFoundException()
    {
        $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")
            ->setPassword("root")->setRoles(["role"]);
        $this->entityManager->persist($user);
        /**
         * @var Recipe[] $recipes
         */
        $recipes = [];
        for ( $i = 0; $i < 25; $i++ )                // <5
        {
            $recipe = $this->createRecipe("t","s","i",["ing"],["ste"],$user);
            $this->entityManager->persist($recipe);
            $recipes[] = $recipe;
        }
        $this->entityManager->flush();

        foreach ($recipes as $testRecipe)
        {
            $testRecipe->setPublicationDate(null);
            $this->entityManager->persist($testRecipe);
        }
        $this->entityManager->flush();
        $this->expectException(RecipeNotFoundException::class);
        $this->recipeRepository->get10Recipe();
    }
    public function testGet10Recipe()
    {
        $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")
            ->setPassword("root")->setRoles(["role"]);

        $this->entityManager->persist($user);

        for ( $i = 0; $i < 25; $i++ )                // <5
        {
            $recipe = $this->createRecipe("t","s","i",["ing"],["ste"],$user);
            $this->entityManager->persist($recipe);

        }
        $this->entityManager->flush();

        $this->assertCount(10,$this->recipeRepository->get10Recipe()); // 5
    }

    public function  testGetPublicRecipesByUser()
    {
        $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")
            ->setPassword("root")->setRoles(["role"]);

        $this->entityManager->persist($user);

        $recipe = $this->createRecipe("t","s","i",["ing"],["ste"],$user);
        $this->entityManager->persist($recipe);

        $recipe2 = $this->createRecipe("t2","s2","i2",["ing2"],["ste2"],$user);
        $this->entityManager->persist($recipe2);

        $this->entityManager->flush();

        $recipe2->setPublicationDate(null);

        $this->entityManager->persist($recipe2);
        $this->entityManager->flush();

        $actual = $this->recipeRepository->getPublicRecipesByUser($user);
        $this->assertCount(1,$actual);
        $this->assertEquals("t",$actual[0]->getTitle());
    }


    public function testGetCountByUser()
    {
        $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")
            ->setPassword("root")->setRoles(["role"]);

        $this->entityManager->persist($user);

        $recipe = $this->createRecipe("t","s","i",["ing"],["ste"],$user);
        $this->entityManager->persist($recipe);

        $recipe2 = $this->createRecipe("t2","s2","i2",["ing2"],["ste2"],$user);
        $this->entityManager->persist($recipe2);

        $this->entityManager->flush();

        $recipe2->setPublicationDate(null);

        $this->entityManager->persist($recipe2);
        $this->entityManager->flush();

        $this->assertEquals(2,$this->recipeRepository->getCountByUser($user));

    }


    public function testGetPrivateCountByUser()
    {
        $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")
            ->setPassword("root")->setRoles(["role"]);

        $this->entityManager->persist($user);

        $recipe = $this->createRecipe("t","s","i",["ing"],["ste"],$user);
        $this->entityManager->persist($recipe);

        $recipe2 = $this->createRecipe("t2","s2","i2",["ing2"],["ste2"],$user);
        $this->entityManager->persist($recipe2);

        $this->entityManager->flush();

        $recipe->setPublicationDate(null);
        $recipe2->setPublicationDate(null);

        $this->entityManager->persist($recipe2);
        $this->entityManager->persist($recipe);

        $this->entityManager->flush();

        $this->assertEquals(2,$this->recipeRepository->getPrivateCountByUser($user));
    }

    public function testGetPublicCountByUser()
    {
        $user = (new User())->setFirstName("f")->setLastName("l")->setEmail("email")
            ->setPassword("root")->setRoles(["role"]);

        $this->entityManager->persist($user);

        $recipe = $this->createRecipe("t","s","i",["ing"],["ste"],$user);
        $this->entityManager->persist($recipe);

        $recipe1 = $this->createRecipe("t","s","i",["ing"],["ste"],$user);
        $this->entityManager->persist($recipe1);

        $recipe2 = $this->createRecipe("t2","s2","i2",["ing2"],["ste2"],$user);
        $this->entityManager->persist($recipe2);

        $this->entityManager->flush();

        $recipe1->setPublicationDate(null);
        $recipe2->setPublicationDate(null);

        $this->entityManager->persist($recipe2);
        $this->entityManager->persist($recipe1);

        $this->entityManager->flush();

        $this->assertEquals(1,$this->recipeRepository->getPublicCountByUser($user));
    }

    private function createRecipe(string $title,string $slug,string $image,array $ingredients,
                                  array $steps,UserInterface $user, ?Cuisine $cuisine = null) : Recipe
    {
          return (new Recipe())
                        ->setTitle($title)
                        ->setSlug($slug)
                        ->setImage($image)
                        ->setIngredients($ingredients)
                        ->setSteps($steps)
                        ->setUser($user)
                        ->setCuisine($cuisine);
    }
}
