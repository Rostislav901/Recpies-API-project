<?php

namespace App\Service;

use App\Entity\User;
use App\Exceptions\UserExistsException;
use App\Model\UserToken;
use App\Model\Registration;
use App\Repository\UserRepository;
use App\Utils\Mapper\EntityMapper;
use App\Utils\ModelMapper;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
     public function __construct(private UserRepository $userRepository,
                                 private EntityMapper $entityMapper,
                                 private JWTTokenManagerInterface $JWTTokenManager)
     {
     }
     public function registration(Registration $request) : string
     {
           if($this->userRepository->existsByEmail($request->getEmail()))
           {
                  throw new UserExistsException();
           }
           $user = $this->entityMapper->mapUserEntity($request);

           $this->userRepository->saveUser($user);

           return  (new UserToken($user,$this->JWTTokenManager))->getToken();
     }
}
