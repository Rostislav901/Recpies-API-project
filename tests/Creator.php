<?php

namespace App\Tests;

use App\Entity\Cuisine;
use App\Entity\Recipe;
use App\Entity\User;
use App\Entity\NewsletterClient;
use App\Model\Registration;
use App\Entity\Review;
use Symfony\Component\Security\Core\User\UserInterface;

class Creator
{
     public static function  createCuisine() : Cuisine
     {
         return  (new Cuisine())
                    ->setTitle("cuisine")
                    ->setSlug("slug");
     }

     public static function createUser() : User
     {
         return  (new User())
                    ->setFirstName("John")
                    ->setLastName("Don")
                    ->setEmail("john.email@com")
                    ->setPassword("password")
                    ->setCountry("GB")
                    ->setAboutMe("description")
                    ->setRoles([Registration::ROLE]);
     }

     public static function createRecipe(UserInterface $cooker, Cuisine $cuisine) : Recipe
     {
         return (new Recipe())
                    ->setCuisine($cuisine)
                    ->setUser($cooker)
                    ->setTitle("title")
                    ->setDescription("description")
                    ->setImage("image")
                    ->setSlug("/slug/")
                    ->setIngredients(["i","b","d"])
                    ->setSteps(["c","v","b"])
                    ->setImages(["img1","img2"]);
     }

     public static function createReview(Recipe $recipe, UserInterface $user) : Review
     {
         return (new Review())
                    ->setRecipe($recipe)
                    ->setUser($user)
                    ->setText("text")
                    ->setRating(4);

     }

     public static function createNewsletterClient() : NewsletterClient
     {
         return  (new NewsletterClient())
                    ->setEmail("test@mail.com");
     }
}
