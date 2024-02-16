<?php

namespace App\Tests\Repository;

use App\Entity\NewsletterClient;
use App\Repository\NewsletterRepository;
use App\Tests\AbstractRepositoryTestCase;

class NewsletterRepositoryTest extends AbstractRepositoryTestCase
{
    private NewsletterRepository $repository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getRepositoryForEntity(NewsletterClient::class);
    }

    public function testExistsByEmailTrue()
    {
          $subscriber = (new NewsletterClient())->setEmail("testSub.email.com");

          $this->entityManager->persist($subscriber);
          $this->entityManager->flush();

          $this->assertTrue($this->repository->existsByEmail("testSub.email.com"));
    }

    public function testExistsByEmailFalse()
    {
        $this->assertFalse($this->repository->existsByEmail("testSub.email.com"));
    }
}
