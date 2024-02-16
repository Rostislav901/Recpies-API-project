<?php

namespace App\Tests\Service;

use App\Entity\NewsletterClient;
use App\Exceptions\NewsletterClientExistsException;
use App\Model\NewsletterClient as Model;
use App\Repository\NewsletterRepository;
use App\Service\NewsletterService;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class NewsletterServiceTest extends TestCase
{
    private  NewsletterRepository $repository;
    private Model $clientModel;
    protected function setUp(): void
    {
        parent::setUp();

        $this->clientModel = (new Model())->setEmail("test@mail.mail");
        $this->repository = $this->createMock(NewsletterRepository::class);
    }

    public function testSubscribeExistException()
    {

          $this->repository->expects($this->once())
                    ->method("existsByEmail")
                    ->with("test@mail.mail")
                    ->willReturn(true);

          $this->expectException(NewsletterClientExistsException::class);

        (new NewsletterService($this->repository))->subscribe($this->clientModel);
    }


    public function testSubscribe()
    {
        $this->repository->expects($this->once())
            ->method("existsByEmail")
            ->with($this->clientModel->getEmail())
            ->willReturn(false);

        $this->repository->expects($this->once())
            ->method("saveSubscribe")
            ->with((new NewsletterClient())->setEmail($this->clientModel->getEmail()));


        (new  NewsletterService($this->repository))->subscribe($this->clientModel);
    }
}
