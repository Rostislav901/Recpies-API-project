<?php

namespace App\Model\RecipeManager;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;



class RequestRecipeModel
{
     #[NotBlank]
     #[Length(min:3, max: 35)]
     private string $title;
     #[NotBlank]
     #[Length(max: 75)]
     private string $description;
     #[NotBlank]
     private array $ingredients;
     #[NotBlank]
     private array $steps;
     #[NotBlank]
     private string $mainImage;
     private ?array $images = [];
     private ?int $cuisineId = null;

     private  DateTimeImmutable $publicDate;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string[]
     */

    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    public function setIngredients(array $ingredients): self
    {
        $this->ingredients = $ingredients;
        return $this;
    }
    /**
     * @return string[]
     */

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function setSteps(array $steps): self
    {
        $this->steps = $steps;
        return $this;
    }

    public function getMainImage(): string
    {
        return $this->mainImage;
    }

    public function setMainImage(string $image): self
    {
        $this->mainImage = $image;
        return $this;
    }

    public function getCuisineId(): ?int
    {
        return $this->cuisineId;
    }

    public function setCuisineId(?int $cuisineId): self
    {
        $this->cuisineId = $cuisineId;
        return $this;
    }
    public function getPublicDate() : DateTimeImmutable
    {
        $this->publicDate = new DateTimeImmutable();

        return $this->publicDate;
    }

    public function getSlug() : string
    {
        $str = strtolower($this->title);
        return  str_replace(" ","-",$str);
    }
    /**
     * @return string[]
     */

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): self
    {
        $this->images = $images;
        return $this;
    }

}
