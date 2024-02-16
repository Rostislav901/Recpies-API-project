<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Helmich\JsonAssert\JsonAssertions;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractControllerTestCase extends WebTestCase
{
       use JsonAssertions;
       protected KernelBrowser $client;
       protected ?EntityManagerInterface $entityManager;
       protected function setUp(): void
       {
           parent::setUp();
           $this->client = static::createClient();
           $this->entityManager = static::getContainer()->get("doctrine.orm.entity_manager");

       }

       protected function tearDown(): void
       {
           parent::tearDown();
           $this->entityManager->close();
           $this->entityManager = null;
       }

    protected function authentication(string $username, string $password): void
    {
        $this->client->request(
            'POST',
            '/api/recipes/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => $username, 'password' => $password], JSON_THROW_ON_ERROR)
        );

        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

    }
}
