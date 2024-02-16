<?php

namespace App\DataFixtures;

use App\Entity\Cuisine;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CuisineFixtures extends Fixture
{
    public const FRENCH_CUISINE_REFERENCE = "French";
    public const ITALIAN_CUISINE_REFERENCE = "Italian";

    public function load(ObjectManager $manager): void
    {
        $french = (new Cuisine())->setTitle("French")->setSlug("/french/");
        $italian = (new Cuisine())->setTitle("Italian")->setSlug("/italian/");

        $cuisines = [
                        self::FRENCH_CUISINE_REFERENCE => $french,
                        self::ITALIAN_CUISINE_REFERENCE => $italian,
            ];

        foreach ($cuisines as $cuisine)
        {
            $manager->persist($cuisine);
        }
        $manager->flush();

        foreach ($cuisines as $reference => $cuisine)
        {
            $this->addReference($reference,$cuisine);
        }
    }
}
