<?php

namespace App\Listener;

use App\Exceptions\ValidationException;
use App\Model\ErrorResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class ValidationExceptionListener
{

    public function __construct(private SerializerInterface $serializer){

    }

    public function __invoke(ExceptionEvent $event)
    {
        $throw = $event->getThrowable();
        if($throw instanceof  ValidationException)
        {
            $data = $this->serializer->serialize(new ErrorResponse($throw->getMessage()),JsonEncoder::FORMAT);
            $response =  new JsonResponse($data,Response::HTTP_BAD_REQUEST,[],true);
            $event->setResponse($response);
        }
    }

}
