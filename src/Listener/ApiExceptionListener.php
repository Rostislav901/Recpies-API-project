<?php

namespace App\Listener;

use App\Model\ErrorResponse;
use App\Service\ExceptionHandler\ExceptionMapping;
use App\Service\ExceptionHandler\ExceptionMappingResolver;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class ApiExceptionListener
{
  public function __construct(private ExceptionMappingResolver $resolver,
                              private LoggerInterface $logger,
                              private SerializerInterface $serializer)
  {
  }

  public function __invoke(ExceptionEvent $event) : void
  {
     $throwable = $event->getThrowable();

     if($this->isSecurityException($throwable))
     {
         return;
     }
     $mapping = $this->resolver->resolve(get_class($throwable));

     if(null === $mapping)
     {
         $mapping =  ExceptionMapping::fromCode(Response::HTTP_INTERNAL_SERVER_ERROR);
     }
     if($mapping->getCode() >= Response::HTTP_INTERNAL_SERVER_ERROR || $mapping->isLoggable())
     {
         $this->logger->error($throwable->getMessage(),["trace"=>$throwable->getTraceAsString()]);
     }

     $message = $mapping->isHidden() ? Response::$statusTexts[$mapping->getCode()] : $throwable->getMessage();
     $data = $this->serializer->serialize(new ErrorResponse($message),JsonEncoder::FORMAT);
     $response =  new JsonResponse($data,$mapping->getCode(),[],true);
     $event->setResponse($response);
  }

    private function isSecurityException(Throwable $throwable) : bool
    {
        return $throwable instanceof  AccessDeniedException || $throwable instanceof  AuthenticationException;
    }
}
