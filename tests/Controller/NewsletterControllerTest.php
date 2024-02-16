<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTestCase;

class NewsletterControllerTest extends AbstractControllerTestCase
{

    public function testSub()
    {
        $content = json_encode(["email" => "testemail@mail.com","agreed" => true]);
        $this->client->request("POST","/api/recipes/newsletter/subscribe",[],[],[],$content);

        $this->assertResponseIsSuccessful();
    }
}
