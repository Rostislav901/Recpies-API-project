<?php

namespace App\Controller;

use App\Attribute\Body;
use App\Model\ErrorResponse;
use App\Model\NewsletterClient;
use App\Service\NewsletterService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class NewsletterController extends AbstractController
{
    public function __construct(private NewsletterService $newsletterService)
    {
    }

    #[OA\Response(response: 200,description: "Get info that you was subscribe")]
    #[OA\Response(response: 400,description: "Validation Error",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 405,description: "Email already exist",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\Response(response: 406,description: "Error during conversion requestData",attachables: [new Model(type: ErrorResponse::class)] )]
    #[OA\RequestBody(attachables: [new Model(type: NewsletterClient::class)])]
    #[Route("/api/recipes/newsletter/subscribe",methods: ["POST"])]
    public function sub(#[Body]NewsletterClient $client): Response
    {

        $this->newsletterService->subscribe($client);
        return $this->json(null);
    }

}
