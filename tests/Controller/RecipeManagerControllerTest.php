<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTestCase;
use App\Controller\RecipeManagerController;
use App\Tests\Creator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RecipeManagerControllerTest extends AbstractControllerTestCase

{
    private UserPasswordHasherInterface $hasher;
    protected function setUp(): void
    {
        parent::setUp();
        $this->hasher = self::getContainer()->get("security.user_password_hasher");
    }

    public function testGet()
    {

         $cooker = Creator::createUser();
         $clearPassword = $cooker->getPassword();
         $cooker->setPassword($this->hasher->hashPassword($cooker,$clearPassword));
         $this->entityManager->persist($cooker);

         $cuisine = Creator::createCuisine();
         $this->entityManager->persist($cuisine);

         $recipe = Creator::createRecipe($cooker,$cuisine);
         $this->entityManager->persist($recipe);

         $recipe = Creator::createRecipe($cooker,$cuisine);
         $this->entityManager->persist($recipe);


         $this->entityManager->flush();

         $this->authentication($cooker->getUserIdentifier(),$clearPassword);

         $this->client->request("GET","/api/recipes/cooker/get/recipe/all");
         $response = json_decode($this->client->getResponse()->getContent());
         $this->assertResponseIsSuccessful();
         $this->assertJsonDocumentMatchesSchema($response,[
             "type" => "object",
             "required" => "recipes",
             "properties" => [
                 "recipes" => [
                     "type" => "array",
                     "items" => [
                         "type" => "object",
                         "required" => ["id","title","cooker","slug","image","cuisine","rating","reviewCount","publicDate"],
                         "properties" => [
                             "id" => ["type" => "integer"],
                             "title" => ["type" => "string"],
                             "cooker" => ["type" => "string"],
                             "slug" => ["type" => "string"],
                             "image" => ["type" => "string"],
                             "cuisine" => ["type" => "string"],
                             "rating" => [
                                 "anyOf" => [
                                     ["type" => null],
                                     ["type" => "number"]
                                 ]
                             ],
                             "reviewCount" => ["type" => "integer"],
                             "publicDate" => ["type" => "integer"]
                         ]
                     ]
                 ]
             ]
         ]);
    }

    public function testDeleteReview()
    {
        $cooker = Creator::createUser();
        $clearPassword = $cooker->getPassword();
        $cooker->setPassword($this->hasher->hashPassword($cooker,$clearPassword));
        $this->entityManager->persist($cooker);

        $cuisine = Creator::createCuisine();
        $this->entityManager->persist($cuisine);

        $recipe = Creator::createRecipe($cooker,$cuisine);
        $this->entityManager->persist($recipe);

        $review = Creator::createReview($recipe,$cooker);
        $this->entityManager->persist($review);

        $this->entityManager->flush();

        $reviewId = $review->getId();
        $this->authentication($cooker->getUserIdentifier(),$clearPassword);
        $this->client->request("DELETE","/api/recipes/review/action/delete/review/$reviewId");

        $this->assertResponseIsSuccessful();
    }

    public function testPublish()
    {
        $cooker = Creator::createUser();
        $clearPassword = $cooker->getPassword();
        $cooker->setPassword($this->hasher->hashPassword($cooker,$clearPassword));
        $this->entityManager->persist($cooker);

        $cuisine = Creator::createCuisine();
        $this->entityManager->persist($cuisine);

        $recipe = Creator::createRecipe($cooker,$cuisine);
        $this->entityManager->persist($recipe);

        $this->entityManager->flush();

        $recipe->setPublicationDate(null);
        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        $recipeId = $recipe->getId();

        $this->authentication($cooker->getUserIdentifier(),$clearPassword);

        $this->client->request("PATCH","/api/recipes/cooker/publish/recipe/$recipeId");

        $this->assertResponseIsSuccessful();
    }

    public function testUnpublish()
    {
        $cooker = Creator::createUser();
        $clearPassword = $cooker->getPassword();
        $cooker->setPassword($this->hasher->hashPassword($cooker,$clearPassword));
        $this->entityManager->persist($cooker);

        $cuisine = Creator::createCuisine();
        $this->entityManager->persist($cuisine);

        $recipe = Creator::createRecipe($cooker,$cuisine);
        $this->entityManager->persist($recipe);

        $this->entityManager->flush();

        $recipeId = $recipe->getId();

        $this->authentication($cooker->getUserIdentifier(),$clearPassword);

        $this->client->request("PATCH","/api/recipes/cooker/unpublish/recipe/$recipeId");

        $this->assertResponseIsSuccessful();
    }

    public function testSubmitReview()
    {
        $cooker = (Creator::createUser())->setEmail("another@mail.com");
        $this->entityManager->persist($cooker);

        $cuisine = Creator::createCuisine();
        $this->entityManager->persist($cuisine);

        $recipe = Creator::createRecipe($cooker,$cuisine);
        $this->entityManager->persist($recipe);

        $submitter = Creator::createUser();
        $clearPassword = $submitter->getPassword();
        $submitter->setPassword($this->hasher->hashPassword($submitter,$clearPassword));
        $this->entityManager->persist($submitter);

        $this->entityManager->flush();

        $recipeId = $recipe->getId();
        $this->authentication($submitter->getUserIdentifier(),$clearPassword);
        $body = [
           "text" => "nice recipe",
           "rating" => 5
        ];

        $this->client->request("POST","/api/recipes/review/action/submit/recipe/$recipeId",
                                [],[],["CONTENT_TYPE" =>"application/json"],json_encode($body));

        $this->assertResponseIsSuccessful();
    }

    public function testUpdateReview()
    {
        $cooker = (Creator::createUser())->setEmail("another@mail.com");
        $clearPassword = $cooker->getPassword();
        $cooker->setPassword($this->hasher->hashPassword($cooker,$clearPassword));
        $this->entityManager->persist($cooker);

        $cuisine = Creator::createCuisine();
        $this->entityManager->persist($cuisine);

        $recipe = Creator::createRecipe($cooker,$cuisine);
        $this->entityManager->persist($recipe);

        $review = Creator::createReview($recipe,$cooker);
        $this->entityManager->persist($review);
        $this->entityManager->flush();

        $reviewId = $review->getId();

        $this->authentication($cooker->getUserIdentifier(),$clearPassword);
        $body = [
            "text" => "nice recipe",
            "rating" => 5
        ];

        $this->client->request("PUT","/api/recipes/review/action/update/review/$reviewId",
            [],[],["CONTENT_TYPE" =>"application/json"],json_encode($body));

        $this->assertResponseIsSuccessful();
    }

    public function testAdd()
    {
        $cooker = (Creator::createUser())->setEmail("another@mail.com");
        $clearPassword = $cooker->getPassword();
        $cooker->setPassword($this->hasher->hashPassword($cooker,$clearPassword));
        $this->entityManager->persist($cooker);


        $this->entityManager->flush();

        $body = [
            "title" => "test",
            "description" => "test-desc",
            "ingredients" => ["milk","sugar"],
            "steps" => ["cook"],
            "mainImage" => "mainImage.png",
        ];

        $this->authentication($cooker->getUserIdentifier(),$clearPassword);

        $this->client->request("POST","/api/recipes/cooker/add/recipe",
                                [],[],["CONTENT_TYPE" =>"application/json"],json_encode($body));

        $this->assertResponseIsSuccessful();
    }

    public function testUpdate()
    {
        $cooker = (Creator::createUser())->setEmail("another@mail.com");
        $clearPassword = $cooker->getPassword();
        $cooker->setPassword($this->hasher->hashPassword($cooker,$clearPassword));
        $this->entityManager->persist($cooker);

        $cuisine = Creator::createCuisine();
        $this->entityManager->persist($cuisine);

        $recipe = Creator::createRecipe($cooker,$cuisine);
        $this->entityManager->persist($recipe);

        $this->entityManager->flush();

        $recipeId = $recipe->getId();
        $body = [
            "title" => "test",
            "description" => "test-desc",
            "ingredients" => ["milk","sugar"],
            "steps" => ["cook"],
            "mainImage" => "mainImage.png",
        ];

        $this->authentication($cooker->getUserIdentifier(),$clearPassword);

        $this->client->request("PUT","/api/recipes/cooker/update/recipe/$recipeId",
            [],[],["CONTENT_TYPE" =>"application/json"],json_encode($body));

        $this->assertResponseIsSuccessful();
    }

    public function testCooker()
    {
        $cooker = (Creator::createUser())->setEmail("another@mail.com");
        $clearPassword = $cooker->getPassword();
        $cooker->setPassword($this->hasher->hashPassword($cooker,$clearPassword));
        $this->entityManager->persist($cooker);


        $this->entityManager->flush();

        $this->authentication($cooker->getUserIdentifier(),$clearPassword);

        $this->client->request("GET","/api/recipes/cooker/about/me");
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($response,[
            "type" => "object",
            "required" => ["fullName","email","registrationDate","aboutMe","country","countRecipes","countPublicRecipes",
                            "countPrivateRecipes","averageRecipesRating","totalReviewCount"],
            "properties" => [
                "fullName" => ["type" => "string"],
                "email" => ["type" => "string"],
                "registrationDate" => ["type" => "integer"],
                "aboutMe" => ["type" => "string"],
                "country" => ["type" => "string"],
                "countRecipes" => ["type" => "integer"],
                "countPublicRecipes" => ["type" => "integer"],
                "countPrivateRecipes" => ["type" => "integer"],
                "averageRecipesRating" => ["type" => "number"],
                "totalReviewCount" => ["type" => "integer"]
            ]
        ]);
    }

    public function testDelete()
    {
        $cooker = Creator::createUser();
        $clearPassword = $cooker->getPassword();
        $cooker->setPassword($this->hasher->hashPassword($cooker,$clearPassword));
        $this->entityManager->persist($cooker);

        $cuisine = Creator::createCuisine();
        $this->entityManager->persist($cuisine);

        $recipe = Creator::createRecipe($cooker,$cuisine);
        $this->entityManager->persist($recipe);

        $this->entityManager->flush();

        $recipeId = $recipe->getId();

        $this->authentication($cooker->getUserIdentifier(),$clearPassword);

        $this->client->request("DELETE","/api/recipes/cooker/delete/recipe/$recipeId");

        $this->assertResponseIsSuccessful();
    }
}
