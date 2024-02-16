<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTestCase;
use App\Tests\Creator;

class RecipeControllerTest extends AbstractControllerTestCase
{

    public function testRecipesByCuisineId()
    {
         $user = Creator::createUser();
         $cuisine = Creator::createCuisine();
         $this->entityManager->persist($user);
         $this->entityManager->persist($cuisine);
         $recipe = Creator::createRecipe($user,$cuisine);
         $this->entityManager->persist($recipe);
         $review =  Creator::createReview($recipe,$user); //
         $this->entityManager->persist($review);
         $this->entityManager->flush();

         $cuisineId = $cuisine->getId();
         $this->client->request("GET","/api/recipes/cuisine/$cuisineId/recipe/all");
         $response = json_decode($this->client->getResponse()->getContent(),true);

         $this->assertResponseIsSuccessful();

         $this->assertJsonDocumentMatchesSchema($response,[
                 "type" => "object",
                 "required" => ["recipes"],
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
                                 "cuisine" => [
                                     "anyOf" => [
                                         ["type" => "string"],
                                         ["type" => null]
                                     ]
                                 ],
                                 "rating" => [
                                     "anyOf" => [
                                         ["type" => "number"],
                                         ["type" => null]
                                     ]
                                 ],
                                 "reviewCount" => ["type" => "integer"],
                                 "publicDate" => ["type" => "integer"]
                         ]
                     ]
                 ]]

             ]
         );
    }

    public function testRecipesById()
    {
        $user = Creator::createUser();
        $cuisine = Creator::createCuisine();
        $this->entityManager->persist($user);
        $this->entityManager->persist($cuisine);
        $recipe = Creator::createRecipe($user,$cuisine);
        $this->entityManager->persist($recipe);
        $review =  Creator::createReview($recipe,$user);
        $this->entityManager->persist($review);
        $this->entityManager->flush();

        $recipeId = $recipe->getId();

        $this->client->request("GET","/api/recipes/recipe/$recipeId");
        $response =  json_decode($this->client->getResponse()->getContent());

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($response,[
            "type" => "object",
            "properties" => [
                "id" => ["type" => "integer"],
                "title" => ["type" => "string"],
                "cooker" => ["type" => "string"],
                "publicationDate" => ["type" => "integer"],
                "slug" => ["type" => "string"],
                "description" => ["type" => "string"],
                "image" => ["type" => "string"],
                "cuisine" => [
                    "anyOf" => [
                        ["type" => "string"],
                        ["type" => null]
                    ]
                ],
                "ingredients" => [
                    "type" => "array",
                    "properties" => ["type" => "string"]
                ],
                "steps" => [
                    "type" => "array",
                    "properties" => ["type" => "string"]
                ],
                "rating" => [
                    "anyOf" => [
                        ["type" => "number"],
                        ["type" => null]
                    ]
                ],
                "reviewCount" => ["type" => "integer"],
                "images" => [
                    "anyOf" => [
                        ["type" => "number","properties" => "string"],
                        ["type" => null]
                    ]
                ]
            ]
        ]);
    }

    public function testPublicRecipeByCookerId()
    {
        $user = Creator::createUser();
        $cuisine = Creator::createCuisine();
        $this->entityManager->persist($user);
        $this->entityManager->persist($cuisine);
        $recipe = Creator::createRecipe($user,$cuisine);
        $this->entityManager->persist($recipe);
        $review =  Creator::createReview($recipe,$user);
        $this->entityManager->persist($review);
        $this->entityManager->flush();

        $userId = $user->getId();

        $this->client->request("GET","/api/recipes/public/cooker/$userId/recipe/all");
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($response,[
            "type" => "object",
            "required" => ["recipes"],
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
                            "cuisine" => [
                                "anyOf" => [
                                    ["type" => "string"],
                                    ["type" => null]
                                ]
                            ],
                            "rating" => [
                                "anyOf" => [
                                    ["type" => "number"],
                                    ["type" => null]
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

    public function testRecipes()
    {
        $user = Creator::createUser();
        $cuisine = Creator::createCuisine();
        $this->entityManager->persist($user);
        $this->entityManager->persist($cuisine);
        $recipe = Creator::createRecipe($user,$cuisine);
        $this->entityManager->persist($recipe);
        $review =  Creator::createReview($recipe,$user);
        $this->entityManager->persist($review);
        $this->entityManager->flush();

        $this->client->request("GET","/api/recipes/main");
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($response,[
            "type" => "object",
            "required" => ["recipes"],
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
                            "cuisine" => [
                                "anyOf" => [
                                    ["type" => "string"],
                                    ["type" => null]
                                ]
                            ],
                            "rating" => [
                                "anyOf" => [
                                    ["type" => "number"],
                                    ["type" => null]
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

}
