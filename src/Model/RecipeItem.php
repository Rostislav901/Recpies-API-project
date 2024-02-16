<?php

namespace App\Model;

class RecipeItem
{
       private int $id;
       private string $title;
       private string $cooker;
       private string $slug;
       private string $image;
       private ?string $cuisine = null;
       private ?float $rating = null;
       private int $reviewCount;
       private ?int $publicDate = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getCooker(): string
    {
        return $this->cooker;
    }

    public function setCooker(string $cooker): self
    {
        $this->cooker = $cooker;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
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

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): self
    {
        $this->rating = $rating;
        return $this;
    }

    public function getReviewCount(): ?int
    {
        return $this->reviewCount;
    }

    public function setReviewCount(?int $reviewCount): self
    {
        $this->reviewCount = $reviewCount;
        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }
    public function getPublicDate(): ?int
    {
        return $this->publicDate;
    }

    public function setPublicDate(?int $publicDate): self
    {
        $this->publicDate = $publicDate;
        return $this;
    }







}
