<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;


class PrivateCooker
{
      private string $fullName;
      private string $email;
      private int $registrationDate;
      private ?string $aboutMe = null;
      private ?string $country = null;
      private int $countRecipes;
      private int $countPublicRecipes;
      private int $countPrivateRecipes;
      private float $averageRecipesRating;
      private int $totalReviewCount;

    public function getFullName(): string
    {
        return $this->fullName;
    }
    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    public function getRegistrationDate(): int
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(int $registrationDate): self
    {
        $this->registrationDate = $registrationDate;
        return $this;
    }

    public function getAboutMe(): ?string
    {
        return $this->aboutMe;
    }

    public function setAboutMe(?string $aboutMe): self
    {
        $this->aboutMe = $aboutMe;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getCountRecipes(): int
    {
        return $this->countRecipes;
    }

    public function setCountRecipes(int $countRecipes): self
    {
        $this->countRecipes = $countRecipes;
        return $this;
    }

    public function getCountPublicRecipes(): int
    {
        return $this->countPublicRecipes;
    }

    public function setCountPublicRecipes(int $countPublicRecipes): self
    {
        $this->countPublicRecipes = $countPublicRecipes;
        return $this;
    }

    public function getCountPrivateRecipes(): int
    {
        return $this->countPrivateRecipes;
    }

    public function setCountPrivateRecipes(int $countPrivateRecipes): self
    {
        $this->countPrivateRecipes = $countPrivateRecipes;
        return $this;
    }

    public function getAverageRecipesRating(): float
    {
        return $this->averageRecipesRating;
    }

    public function setAverageRecipesRating(float $averageRecipesRating): self
    {
        $this->averageRecipesRating = $averageRecipesRating;
        return $this;
    }

    public function getTotalReviewCount(): int
    {
        return $this->totalReviewCount;
    }

    public function setTotalReviewCount(int $totalReviewCount): self
    {
        $this->totalReviewCount = $totalReviewCount;
        return $this;
    }

}
