<?php

namespace App\Tests\Listener;

use App\Listener\ApiExceptionListener;
use App\Model\ErrorResponse;
use App\Service\ExceptionHandler\ExceptionMapping;
use App\Service\ExceptionHandler\ExceptionMappingResolver;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\Event;

class ApiExceptionListenerTest extends AbstractExceptionListenerTestCase
{
      public function testNot500()
      {
           $mapping = ExceptionMapping::fromCode(Response::HTTP_NOT_FOUND);
           $message = Response::$statusTexts[$mapping->getCode()];
           $expectedResponse = json_encode($message);

           $this->resolver->expects($this->once())
                        ->method("resolve")
                        ->with(\LogicException::class)
                        ->willReturn($mapping);


           $this->serializer->expects($this->once())
                        ->method("serialize")
                        ->with(new ErrorResponse($message),JsonEncoder::FORMAT)
                        ->willReturn($expectedResponse);

           $exceptionEvent =  $this->createEvent(new \LogicException());

           $listener = new ApiExceptionListener($this->resolver,$this->logger,$this->serializer);
           $listener($exceptionEvent);

           $actualResponse = $exceptionEvent->getResponse();
           $this->assertEquals(Response::HTTP_NOT_FOUND,$actualResponse->getStatusCode());
           $this->assertEquals($expectedResponse,$actualResponse->getContent());
      }

      public function testSecurityException()
      {

          $exceptionEvent =  $this->createEvent(new class() extends  AccessDeniedException{});

          $listener = new ApiExceptionListener($this->resolver,$this->logger,$this->serializer);
          $listener($exceptionEvent);

          $this->assertNull($exceptionEvent->getResponse());
      }


      public function test500Mapping()
      {
          $mapping = ExceptionMapping::fromCode(Response::HTTP_INTERNAL_SERVER_ERROR);
          $message = Response::$statusTexts[$mapping->getCode()];

          $expectedResponse = json_encode($message);
          $this->resolver->expects($this->once())
                        ->method("resolve")
                        ->with(\LogicException::class)
                        ->willReturn(null);
          $this->logger->expects($this->once())
                    ->method("error");

          $this->serializer->expects($this->once())
              ->method("serialize")
              ->with(new ErrorResponse($message),JsonEncoder::FORMAT)
              ->willReturn($expectedResponse);

          $exceptionEvent =  $this->createEvent(new \LogicException());

          $listener = new ApiExceptionListener($this->resolver,$this->logger,$this->serializer);
          $listener($exceptionEvent);

          $actualResponse = $exceptionEvent->getResponse();
          $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR,$actualResponse->getStatusCode());
          $this->assertEquals($expectedResponse,$actualResponse->getContent());

      }

      public function testNot500MappingLoggerCall()
      {
          $mapping = new ExceptionMapping(Response::HTTP_NOT_FOUND,false,true);
          $message = "test";
          $expectedResponse = json_encode($message);

          $this->resolver->expects($this->once())
              ->method("resolve")
              ->with(\LogicException::class)
              ->willReturn($mapping);


          $this->serializer->expects($this->once())
              ->method("serialize")
              ->with(new ErrorResponse($message),JsonEncoder::FORMAT)
              ->willReturn($expectedResponse);

          $this->logger->expects($this->once())
                         ->method("error");

          $exceptionEvent =  $this->createEvent(new \LogicException("test"));

          $listener = new ApiExceptionListener($this->resolver,$this->logger,$this->serializer);
          $listener($exceptionEvent);

          $actualResponse = $exceptionEvent->getResponse();
          $this->assertEquals(Response::HTTP_NOT_FOUND,$actualResponse->getStatusCode());
          $this->assertEquals($expectedResponse,$actualResponse->getContent());
      }



}
