<?php

namespace App\Tests\Listener;

use App\Exceptions\ValidationException;
use App\Listener\ValidationExceptionListener;
use App\Model\ErrorResponse;
use App\Utils\Mapper\Mapper;
use LogicException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class ValidationExceptionListenerTest extends AbstractExceptionListenerTestCase
{


     public function testValidationException()
     {
         $response = json_encode("validationError");

         $this->serializer->expects($this->once())
                        ->method("serialize")
                        ->with(new ErrorResponse("validationError"),JsonEncoder::FORMAT)
                        ->willReturn($response);

         $event = $this->createEvent(new class extends ValidationException{});

         $listener = new ValidationExceptionListener($this->serializer);
         $listener($event);

         $actualResponse = $event->getResponse();

         $this->assertEquals(Response::HTTP_BAD_REQUEST,$actualResponse->getStatusCode());
         $this->assertEquals($response,$actualResponse->getContent());
     }

    public function testNotValidationException()
    {
        $event = $this->createEvent(new LogicException());
        $listener = new ValidationExceptionListener($this->serializer);
        $listener($event);

        $this->assertNull($event->getResponse());
    }
}
