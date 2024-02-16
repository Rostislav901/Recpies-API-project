<?php

namespace App\Tests\Listener;

use App\Service\ExceptionHandler\ExceptionMappingResolver;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\Event;

class AbstractExceptionListenerTestCase extends TestCase
{
    protected LoggerInterface $logger;
    protected SerializerInterface $serializer;
    protected ExceptionMappingResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->resolver = $this->createMock(ExceptionMappingResolver::class);
    }
    protected function createEvent(\Exception $exception) : ExceptionEvent
    {
        return  new  ExceptionEvent(
            $this->createKernel(),
            new Request(),
            HttpKernelInterface::MAIN_REQUEST,
            $exception,
        );


    }

    protected function createKernel() : HttpKernelInterface
    {
        return new class() implements HttpKernelInterface
        {
            public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
            {
                return  new Response();
            }
        };
    }
}
