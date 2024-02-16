<?php

namespace App\Tests\Controller;

use App\Controller\RegistrationController;
use App\Tests\AbstractControllerTestCase;
use PHPUnit\Framework\TestCase;

class RegistrationControllerTest extends AbstractControllerTestCase
{

    public function testRegistration()
    {
        $body = json_encode(["firstName"=>"Owner","lastName"=>"Til","email"=>"test123@mail.com",
                             "password"=>"moreThan6","country"=>"GB","aboutMe"=>"Hello","confirmPassword"=>"moreThan6"]);


        $this->client->request("POST","/api/recipes/registration",[],[],[],$body);

        $this->assertResponseIsSuccessful();
    }
}
