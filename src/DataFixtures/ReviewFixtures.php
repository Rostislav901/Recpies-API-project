<?php

namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i<6; $i++)
        {
             $ref = $i % 2 == 0 ?  RecipeFixtures::PASTA_RECIPE_REFERENCE : RecipeFixtures::BRUSCHETTA_RECIPE_REFERENCE;
             $review = (new Review())
                        ->setUser($this->getReference(UserFixtures::HECTOR_JIMENEZ_BRAVO_REFERENCE))
                        ->setRecipe($this->getReference($ref))
                        ->setRating($i*2)
                        ->setText("review by hector $i");

             $manager->persist($review);
        }

        for ($k = 1; $k<6; $k++)
        {
            $ref = $k % 2 == 0 ?  RecipeFixtures::RATATOUILLE_RECIPE_REFERENCE : RecipeFixtures::BRUSCHETTA_RECIPE_REFERENCE;
            $review = (new Review())
                ->setUser($this->getReference(UserFixtures::GORDON_RAMSEY_REFERENCE))
                ->setRecipe($this->getReference($ref))
                ->setRating($k)
                ->setText("review by gordon $k");

            $manager->persist($review);
        }

        for ($j = 1; $j<6; $j++)
        {
            $ref = $j % 2 == 0 ?  RecipeFixtures::PASTA_RECIPE_REFERENCE : RecipeFixtures::RATATOUILLE_RECIPE_REFERENCE;
            $review = (new Review())
                ->setUser($this->getReference(UserFixtures::JAMIE_OLIVER_REFERENCE))
                ->setRecipe($this->getReference($ref))
                ->setRating($j)
                ->setText("review by oliver $j");

            $manager->persist($review);
        }
        $manager->flush();

    }

    public function getDependencies() : array
    {
        return  [
            UserFixtures::class,
            RecipeFixtures::class
        ];
    }
}
