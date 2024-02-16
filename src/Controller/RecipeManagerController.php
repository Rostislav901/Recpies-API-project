<?php

namespace App\Controller;

use App\Attribute\Body;
use App\Model\ErrorResponse;
use App\Model\PrivateCooker;
use App\Model\Recipe;
use App\Model\RecipeManager\RequestRecipeModel;
use App\Model\SubmitReview;
use App\Service\RecipeMangerServices\AddRecipeService;
use App\Service\RecipeMangerServices\DeleteRecipeService;
use App\Service\RecipeMangerServices\GetRecipeService;
use App\Service\RecipeMangerServices\UpdateRecipeService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RecipeManagerController extends AbstractController
{
    public function __construct(private readonly AddRecipeService $addRecipeService,
                                private readonly DeleteRecipeService $deleteRecipeService,
                                private readonly GetRecipeService $getRecipeService,
                                private readonly UpdateRecipeService $updateRecipeService)
    {
    }
    #[OA\Response(response: 200,description: "Get info about authenticated user",attachables: [new Model(type: PrivateCooker::class)])]
    #[OA\Header(header: "Authorization", description: "Bearer {token}",  required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 401, description: "Authorization was failed. Check you token.",attachables: [new Model(type: ErrorResponse::class)] )]
    #[Route("api/recipes/cooker/about/me",methods: ["GET"])]
    public function cooker(): Response
    {
        return $this->json($this->getRecipeService->getCookerInfo());
    }
    #[OA\Response(response: 201,description: "Add recipe by authenticated user")]
    #[OA\Response(response: 401, description: "Authorization was failed. Check you token.",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 400,description: "Validation Error",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 406,description: "Error during conversion requestData",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Header(header: "Authorization", description: "Bearer {token}",  required: true, schema: new OA\Schema(type: "string"))]
    #[OA\RequestBody(attachables: [new Model(type:RequestRecipeModel::class)])]
    #[Route("/api/recipes/cooker/add/recipe",methods: ["POST"])]
    public function add(#[Body]RequestRecipeModel $addRecipe) : Response
    {
        $this->addRecipeService->addRecipe($addRecipe);
        return  $this->json(null );
    }
    #[OA\Response(response: 204,description: "Delete recipe by authenticated user")]
    #[OA\Response(response: 401, description: "Authorization was failed. Check you token.",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 404,description: "Recipe not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Header(header: "Authorization", description: "Bearer {token}",  required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Parameter(name: "id", description: "recipe id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[Route("/api/recipes/cooker/delete/recipe/{id}",methods: ["DELETE"])]
    public function delete(int $id) : Response
    {
        $this->deleteRecipeService->deleteRecipe($id);
        return  $this->json(null);
    }
    #[OA\Response(response: 200,description: "Return all recipes that authenticated user create",attachables: [new Model(type: Recipe::class)])]
    #[OA\Response(response: 401, description: "Authorization was failed. Check you token.",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 404,description: "Recipe not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Header(header: "Authorization", description: "Bearer {token}",  required: true, schema: new OA\Schema(type: "string"))]
    #[Route("/api/recipes/cooker/get/recipe/all",methods: ["GET"])]
    public function get() : Response
    {
          return $this->json($this->getRecipeService->getRecipes());
    }
    #[OA\Response(response: 203,description: "Update recipe by authenticated user.  Return null")]
    #[OA\Response(response: 401, description: "Authorization was failed. Check you token.",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 400,description: "Validation Error",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 406,description: "Error during conversion requestData",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 404,description: "Recipe not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Header(header: "Authorization", description: "Bearer {token}",  required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Parameter(name: "id", description: "recipe id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\RequestBody(attachables: [new Model(type:RequestRecipeModel::class)])]
    #[Route("/api/recipes/cooker/update/recipe/{id}",methods: ["PUT"])]
    public function update(int $id, #[Body]RequestRecipeModel $model) : Response
    {
        $this->updateRecipeService->updateRecipe($id,$model);
        return $this->json(null);
    }
    #[OA\Response(response: 200,description: "Set date-value of publicDate-field by authenticated user. Return null ")]
    #[OA\Response(response: 401, description: "Authorization was failed. Check you token.",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 404,description: "Recipe not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 405,description: "Recipe already publish",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Header(header: "Authorization", description: "Bearer {token}",  required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Parameter(name: "id", description: "recipe id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[Route("/api/recipes/cooker/publish/recipe/{id}",methods: ["PATCH"])]
    public function publish(int $id) : Response
    {
         $this->updateRecipeService->publish($id);
         return $this->json(null);
    }
    #[OA\Response(response: 200,description: "Set null-value of publicDate-field by authenticated user. Return null ")]
    #[OA\Response(response: 401, description: "Authorization was failed. Check you token.",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 404,description: "Recipe not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 405,description: "Recipe already unpublish",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Header(header: "Authorization", description: "Bearer {token}",  required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Parameter(name: "id", description: "recipe id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[Route("/api/recipes/cooker/unpublish/recipe/{id}",methods: ["PATCH"])]
    public function unpublish(int $id) : Response
    {
        $this->updateRecipeService->unpublish($id);
        return $this->json(null);
    }
    #[OA\Response(response: 201,description: "Submit review by authenticated user. Return null")]
    #[OA\Response(response: 401, description: "Authorization was failed. Check you token.",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 400,description: "Validation Error",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 404,description: "Recipe not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 408,description: "User rule error",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 406,description: "Error during conversion requestData",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Header(header: "Authorization", description: "Bearer {token}",  required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Parameter(name: "id", description: "recipe id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\RequestBody(attachables: [new Model(type:SubmitReview::class)])]
    #[Route(path: "/api/recipes/review/action/submit/recipe/{id}",methods: ["POST"])]
    public function submitReview(int $id,#[Body]SubmitReview $submitReview) : Response
    {
         $this->addRecipeService->addReview($id,$submitReview);
         return $this->json(null);
    }
    #[OA\Response(response: 204,description: "Delete review by authenticated user. Return null")]
    #[OA\Response(response: 405,description: "Recipe not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 401, description: "Authorization was failed. Check you token again.",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 408,description: "User rule error",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Header(header: "Authorization", description: "Bearer {token}",  required: true, schema: new OA\Schema(type: "string"))]
    #[OA\Parameter(name: "id", description: "review id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[Route(path: "/api/recipes/review/action/delete/review/{id}",methods: ["DELETE"])]

    public function deleteReview(int $id) : Response
    {
        $this->deleteRecipeService->deleteReview($id);
        return  $this->json(null);
    }
    #[OA\Response(response: 200,description: "Update review by creator. Return null. ")]
    #[OA\Response(response: 405,description: "Recipe not found",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 401, description: "Authorization was failed. Check you token again.",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 408,description: "User rule error",attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 400,description: "Validation Error",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 406,description: "Error during conversion requestData",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Parameter(name: "id", description: "review id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[Route(path: "/api/recipes/review/action/update/review/{id}",methods: ["PUT"])]
    public function updateReview(int $id,#[Body]SubmitReview $submitReview) : Response
    {
        $this->updateRecipeService->updateReview($id,$submitReview);
        return $this->json(null);
    }

}
