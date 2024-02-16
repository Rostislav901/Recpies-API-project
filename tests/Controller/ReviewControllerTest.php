<?php

namespace App\Tests\Controller;

use App\Controller\ReviewController;
use App\Tests\AbstractControllerTestCase;
use App\Tests\Creator;
use PHPUnit\Framework\TestCase;

class ReviewControllerTest extends AbstractControllerTestCase
{

    public function testGetReviewsByRecipeId()
    {
          $user = Creator::createUser();
          $this->entityManager->persist($user);

          $cuisine = Creator::createCuisine();
          $this->entityManager->persist($cuisine);

          $recipe = Creator::createRecipe($user,$cuisine);
          $this->entityManager->persist($recipe);

          $review = Creator::createReview($recipe,$user);  // make it only for test, but its crush of program logic
          $review1 = Creator::createReview($recipe,$user);
          $this->entityManager->persist($review);
          $this->entityManager->persist($review1);

          $this->entityManager->flush();

          $recipeId = $recipe->getId();

          $this->client->request("GET","/api/recipes/recipe/$recipeId/review/all");
          $response  = json_decode($this->client->getResponse()->getContent());

          $this->assertResponseIsSuccessful();
          $this->assertJsonDocumentMatchesSchema($response,[
                "type" => "object",
                "required" => ["reviews"],
                "properties" => [
                    "reviews" => [
                        "type" => "array",
                        "items"  => [
                            "type" => "object",
                             "required" => ["id","rating","text","date","author"],
                             "properties" => [
                                 "id" => ["type" => "integer"],
                                 "rating" => ["type" => "integer"],
                                 "text" => ["type" => "string"],
                                 "date" => ["type" => "integer"],
                                 "author" => ["type" => "string"]
                             ]
                        ]
                    ]
                ]

          ]);
    }
}
