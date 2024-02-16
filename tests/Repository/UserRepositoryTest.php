<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Exceptions\UserNotFoundException;
use App\Repository\UserRepository;
use App\Tests\AbstractRepositoryTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepositoryTest extends AbstractRepositoryTestCase
{
    protected UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->getRepositoryForEntity(User::class);
    }

    public function testExistsByEmailReturnTrue()
    {
         $user = $this->createUser("name","surname","email","password",["manager"]);

         $this->entityManager->persist($user);
         $this->entityManager->flush();

         $this->assertTrue($this->userRepository->existsByEmail("email"));

    }

    public function testExistsByEmailReturnFalse()
    {
        $this->assertFalse($this->userRepository->existsByEmail("email"));
    }

    public function testGetByIdUserNotFoundException()
    {
        $this->expectException(UserNotFoundException::class);

        $this->userRepository->getById(3);
    }

    public function testGetById()
    {
         $user = $this->createUser("name","surname","email","password",["manager"]);

         $this->entityManager->persist($user);
         $this->entityManager->flush();

         $actual = $this->userRepository->getById($user->getId());
         $this->assertEquals(["email","password"],[$actual->getEmail(),$actual->getPassword()]);

    }

    public function testGetFullNameByIdUserNotFoundException()
    {
         $this->expectException(UserNotFoundException::class);

         $this->userRepository->getFullNameById(3);
    }

    public function testGetFullNameById ()
    {
        $user = $this->createUser("name","surname","email","password",["manager"]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();


        $this->assertEquals("name surname",$this->userRepository->getFullNameById($user->getId()));
    }

    private function createUser(string $firstName,string $lastName,
                                string $email,string $password,array $roles) : User
    {
         return (new User())
                        ->setFirstName($firstName)
                        ->setLastName($lastName)
                        ->setEmail($email)
                        ->setPassword($password)
                        ->setRoles($roles);
    }
}
