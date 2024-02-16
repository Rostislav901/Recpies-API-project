<?php

namespace App\Model;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserToken
{
    private string $token;
      public function __construct(private UserInterface $user, private JWTTokenManagerInterface $JWTTokenManager)
      {
      }

    private function createUserToken() :void
    {
        $this->token = $this->JWTTokenManager->create($this->user);
    }

    public function getToken() : string
    {
        $this->createUserToken();
        return  $this->token;
    }



}
