<?php

namespace App\Controller;

use App\Model\ErrorResponse;
use App\Model\Recipe;
use App\Model\RecipeExtensive;
use App\Service\RecipeService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RecipeController extends AbstractController
{

    public function __construct(private readonly RecipeService $recipeService)
    {
    }

    #[OA\Response(response: 200, description: "Get recipe by cuisinesId",attachables: [new Model(type: Recipe::class)])]
    #[OA\Response(response: 404, description: "Recipe  not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 403, description: "Cuisine with searching id not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Parameter(name: "id", description: "cuisine id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[Route("/api/recipes/cuisine/{id}/recipe/all",methods: ["GET"])]
    public function recipesByCuisineId(int $id): Response
    {
        return  $this->json($this->recipeService->getRecipesByCuisineIdShortForm($id));
    }


    #[OA\Response(response: 200,description: "Random ten recipes",attachables: [new Model(type: Recipe::class)])]
    #[OA\Response(response: 404,description: "Recipe not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[Route("/api/recipes/main",methods: ["GET"])]
    public function recipes() : Response
    {
        return $this->json($this->recipeService->getRecipesShortFrom());
    }
    #[OA\Response(response: 200,description: "Recipe by id",attachables: [new Model(type: RecipeExtensive::class)])]
    #[OA\Response(response: 404,description: "Recipe not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Parameter(name: "id", description: "recipe id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[Route("/api/recipes/recipe/{id}",methods: ["GET"])]
    public function recipesById(int $id) : Response
    {
        return  $this->json($this->recipeService->getRecipeByIdExtendForm($id));
    }

    #[OA\Response(response: 200,description: " Get accessible recipes by cooker Id",attachables: [new Model(type: Recipe::class)])]
    #[OA\Response(response: 404,description: "Recipe not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 407,description: "Cooker not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Parameter(name: "id", description: "cooker id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[Route(path: "/api/recipes/public/cooker/{id}/recipe/all",methods: ["GET"])]
    public function publicRecipeByCookerId(int $id) : Response
    {
        return  $this->json($this->recipeService->getPublicRecipesByCooker($id));
    }
}
