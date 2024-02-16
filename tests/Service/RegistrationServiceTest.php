<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Exceptions\UserExistsException;
use App\Model\Registration;
use App\Repository\UserRepository;
use App\Service\RegistrationService;
use App\Utils\Mapper\EntityMapper;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Framework\TestCase;

class RegistrationServiceTest extends TestCase
{
    private UserRepository $userRepository;
    private EntityMapper $entityMapper;
    private Registration $request;
    private JWTTokenManagerInterface $JWTTokenManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->entityMapper = $this->createMock(EntityMapper::class);
        $this->JWTTokenManager = $this->createMock(JWTTokenManagerInterface::class);
        $this->request = (new Registration())->setEmail("test");
    }

    public function testRegistrationThrowUserExistsException()
    {
         $this->userRepository->expects($this->once())
             ->method("existsByEmail")
             ->with("test")
             ->willReturn(true);


         $this->expectException(UserExistsException::class);

        (new  RegistrationService($this->userRepository,$this->entityMapper,$this->JWTTokenManager))->registration($this->request);
    }


    public function testRegistration()
    {
        $user = (new User())->setFirstName("first-test")->setLastName("second-test");

        $this->entityMapper->expects($this->once())
            ->method("mapUserEntity")
            ->with($this->request)
            ->willReturn($user);

        $this->userRepository->expects($this->once())
            ->method("saveUser")
            ->with($user);

        $this->JWTTokenManager->expects($this->once())
            ->method("create")
            ->with($user)
            ->willReturn("test-token");

        $service = (new  RegistrationService($this->userRepository,$this->entityMapper,$this->JWTTokenManager))
                    ->registration($this->request);

        $this->assertEquals("test-token",$service);
    }
}
