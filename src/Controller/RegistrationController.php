<?php

namespace App\Controller;

use App\Attribute\Body;
use App\Attribute\NewsSubscribe;
use App\Model\ErrorResponse;
use App\Model\Registration;
use App\Service\RegistrationService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\UserToken;


class RegistrationController extends AbstractController
{

    public function __construct(private RegistrationService $registrationService)
    {
    }
    #[OA\Response(response: 200,description: "Informs you that you have registered and return jwt-token",attachables: [new Model(type:UserToken::class)])]
    #[OA\Response(response: 400,description: "Validation Error",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 405,description: "User already exist",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 406,description: "Error during conversion requestData",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\RequestBody(attachables: [new Model(type: Registration::class)])]
    #[Route("/api/recipes/registration",methods: ["POST"])]
    public function registration(#[Body]Registration $request): Response
    {
        return $this->json(["token" => $this->registrationService->registration($request)]);
    }
}
