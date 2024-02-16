<?php

namespace App\Controller;

use App\Service\CuisineService;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Model\Cuisine;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;


class CuisinesController extends AbstractController
{
   public function __construct(private readonly CuisineService $cuisinesService)
   {
   }

    #[OA\Response(response: 200, description: "get all cuisines",attachables: [new Model(type: Cuisine::class)])]
    #[Route("/api/recipes/cuisine/all",methods: ["GET"])]
    public function cuisines(): Response
    {
        return  $this->json($this->cuisinesService->getCuisines());
    }


}
