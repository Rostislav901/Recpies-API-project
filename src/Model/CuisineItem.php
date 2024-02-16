<?php

namespace App\Model;

class CuisineItem
{
    private int $id;
    private string $title;
    private string $slug;

    public function getTitle(): string
    {
        return $this->title;
    }


    public function getSlug(): string
    {
        return $this->slug;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

}
