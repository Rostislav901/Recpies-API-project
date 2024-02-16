<?php

namespace App\DataFixtures;

use App\Entity\NewsletterClient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NewsletterClientFixtures extends Fixture
{
     public function load(ObjectManager $manager)
     {
         for ($i = 1; $i < 6; $i++)
         {
             $manager->persist((new NewsletterClient())->setEmail("ByFixtures$i@testMail.com"));
         }

         $manager->flush();
     }
}
