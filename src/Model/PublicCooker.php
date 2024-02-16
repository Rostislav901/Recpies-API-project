<?php

namespace App\Model;

class PublicCooker
{
      private string $name;
      private int $registrationDate;
      private ?string $cookerInfo = null;
      private ?string $country = null;
      private int $recipeCount;
      private float $recipeAverageRating;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
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

    public function getCookerInfo(): ?string
    {
        return $this->cookerInfo;
    }

    public function setCookerInfo(?string $cookerInfo): self
    {
        $this->cookerInfo = $cookerInfo;
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

    public function getRecipeCount(): int
    {
        return $this->recipeCount;
    }

    public function setRecipeCount(int $recipeCount): self
    {
        $this->recipeCount = $recipeCount;
        return $this;
    }

    public function getRecipeAverageRating(): float
    {
        return $this->recipeAverageRating;
    }

    public function setRecipeAverageRating(float $recipeAverageRating): self
    {
        $this->recipeAverageRating = $recipeAverageRating;
        return $this;
    }


}
