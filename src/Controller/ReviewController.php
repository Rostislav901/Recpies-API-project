<?php

namespace App\Controller;

use App\Model\ErrorResponse;
use App\Service\ReviewService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\Review;


class ReviewController extends AbstractController
{
    public function __construct( private readonly ReviewService $reviewService){}
    #[OA\Response(response: 200,description: "Get all reviews by recipeId",attachables: [new Model(type: Review::class)])]
    #[OA\Response(response: 404,description: "Recipe not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 405,description: "Reviews not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Parameter(name: "id", description: "recipe id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[Route("/api/recipes/recipe/{id}/review/all",methods: ["GET"])]
    public function getReviewsByRecipeId($id): Response
    {
        return $this->json($this->reviewService->getReviewsByRecipe($id));
    }
}
