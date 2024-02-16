<?php

namespace App\Model;

class RecipeExtensive
{

    private int $id;
    private string $title;
    private string $cooker;
    private int $publicationDate;
    private string $slug;
    private string $description;
    private string $image;
    private ?string $cuisine = null;
    private array $ingredients;
    private array $steps;
    private ?float $rating = null;
    private int $reviewCount;
    private ?array $images = null;
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): static
    {
        $this->id = $id;
        return  $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return  $this;
    }

    public function getCooker(): string
    {
        return $this->cooker;
    }

    public function setCooker(string $cooker): static
    {
        $this->cooker = $cooker;
        return $this;

    }

    public function getPublicationDate(): int
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(int $publicationDate): static
    {
        $this->publicationDate = $publicationDate;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return  $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return  $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return  $this;
    }

    public function getCuisine(): ?string
    {
        return $this->cuisine;
    }

    public function setCuisine(?string $cuisine): self
    {
        $this->cuisine = $cuisine;
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

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): self
    {
        $this->rating = $rating;
        return $this;
    }

    public function getReviewCount(): int
    {
        return $this->reviewCount;
    }
    public function setReviewCount(int $reviewCount): self
    {
        $this->reviewCount = $reviewCount;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(?array $images): self
    {
        $this->images = $images;
        return $this;
    }







}
