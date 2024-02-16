<?php
namespace App\Tests\Controller;

use App\Controller\CuisinesController;
use App\Entity\Cuisine;
use App\Tests\AbstractControllerTestCase;
use App\Tests\Creator;
use PHPUnit\Framework\TestCase;

class CuisinesControllerTest extends AbstractControllerTestCase
{

    public function testCuisines()
    {
           $this->entityManager->persist(Creator::createCuisine());
           $this->entityManager->flush();
           $this->client->request("GET","/api/recipes/cuisine/all");
           $response = json_decode($this->client->getResponse()->getContent());

           $this->assertResponseIsSuccessful();
           $this->assertJsonDocumentMatchesSchema($response,[
               "type" => "object",
               "required" => ["cuisines"],
               "properties" => [
                   "cuisines" =>
                                [
                                    "type" => "array",
                                     "items" => [
                                         "type" => "object",
                                          "required" => ["id","title","slug"],
                                           "properties" => [
                                                "id" => ["type" => "integer"],
                                                "title" => ["type" => "string"],
                                                "slug" => ["type" => "string"]
                                           ]
                                     ]
                                ]
               ]
           ]);


    }
}
