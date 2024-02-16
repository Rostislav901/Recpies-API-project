<?php

namespace App\Controller;

use App\Model\ErrorResponse;
use App\Model\PublicCooker;
use App\Service\CookerService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CookerController extends AbstractController
{

    public function __construct(private readonly CookerService $cookerService)
    {
    }
    #[OA\Response(response: 200,description: "Get info about user",attachables: [new Model(type: PublicCooker::class )])]
    #[OA\Response(response: 407,description: "Cooker Not Found",attachables: [new Model(type: ErrorResponse::class )])]
    #[OA\Parameter(name: "id", description: "cooker id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[Route(path: "/api/recipes/public/cooker/{id}/info",methods: ["GET"])]
    public function cooker(int $id): Response
    {
        return $this->json($this->cookerService->getCookerById($id));
    }
}
