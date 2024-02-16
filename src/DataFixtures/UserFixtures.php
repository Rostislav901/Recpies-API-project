<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Model\Registration;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserFixtures extends Fixture
{
      public const HECTOR_JIMENEZ_BRAVO_REFERENCE = "Hector";
      public const GORDON_RAMSEY_REFERENCE = "Gordon";
      public const JAMIE_OLIVER_REFERENCE = "Jamie";
      public function __construct(private UserPasswordHasherInterface $hasher)
      {
      }

      public function load(ObjectManager $manager)
      {
           $hektor = (new User())
                        ->setFirstName("Hector")
                        ->setLastName("Jimenez Bravo")
                        ->setEmail("hector@mail.com")
                        ->setCountry("CO")
                        ->setAboutMe("Hello, im Hektor from Columbia. Na balkon.")
                        ->setRoles([Registration::ROLE]);
           $hektor->setPassword($this->getPassword($hektor,"HectorPassword"));

          $gordon = (new User())
              ->setFirstName("Gordon")
              ->setLastName("Ramsey")
              ->setEmail("gordon@mail.com")
              ->setCountry("GB")
              ->setAboutMe("Hello, im Gordon.mmm delicious.")
              ->setRoles([Registration::ROLE]);
          $gordon->setPassword($this->getPassword($gordon,"GordonPassword"));

          $jamie = (new User())
              ->setFirstName("Jamie")
              ->setLastName("Oliver")
              ->setEmail("oliver@mail.com")
              ->setCountry("GB")
              ->setAboutMe("Hello, im Oliver.all.")
              ->setRoles([Registration::ROLE]);
          $jamie->setPassword($this->getPassword($jamie,"JamiePassword"));

          $manager->persist($hektor);
          $manager->persist($gordon);
          $manager->persist($jamie);

          $manager->flush();

          $cookers = [
              self::HECTOR_JIMENEZ_BRAVO_REFERENCE => $hektor,
              self::GORDON_RAMSEY_REFERENCE => $gordon,
              self::JAMIE_OLIVER_REFERENCE => $jamie
          ];

          foreach ($cookers as $reference => $cooker)
          {
              $this->addReference($reference,$cooker);
          }

      }


      public function getPassword(PasswordAuthenticatedUserInterface $user,string $password)
      {
          return $this->hasher->hashPassword($user,$password);
      }
}
