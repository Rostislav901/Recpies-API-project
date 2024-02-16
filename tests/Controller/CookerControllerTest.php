<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTestCase;
use App\Tests\Creator;
class CookerControllerTest extends AbstractControllerTestCase
{

      public function testCooker()
      {
          $cooker = Creator::createUser();
          $this->entityManager->persist($cooker);
          $this->entityManager->flush();

          $cookerId = $cooker->getId();

          $this->client->request("GET","/api/recipes/public/cooker/$cookerId/info");
          $response = json_decode($this->client->getResponse()->getContent());

          $this->assertResponseIsSuccessful();
          $this->assertJsonDocumentMatchesSchema($response,[
              "type" => "object",
              "required" => ["name","registrationDate","cookerInfo","country","recipeCount","recipeAverageRating"],
              "properties" => [
                  "name" => ["type" => "string"],
                  "registrationDate" => ["type" => "integer"],
                  "cookerInfo" => [
                      "anyOf" => [
                          ["type" => "string"],
                          ["type" => null],
                      ]
                  ],
                  "country" => [
                      "anyOf" => [
                          ["type" => "string"],
                          ["type" => null],
                      ]
                  ],
                  "recipeCount" => ["type" => "integer"],
                  "recipeAverageRating" => ["type" => "number"]
              ]

          ]);
      }

}
