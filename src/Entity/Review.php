<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(type: "integer")]
    private int $rating;
    #[ORM\Column(type: "string",length: 255)]
    private string $text;
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $reviewPublicDate;
    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: User::class,cascade: ["persist"])]
    private UserInterface $user;
    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: Recipe::class,inversedBy: "reviews")]
    private Recipe $recipe;

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function getReviewPublicDate(): DateTimeImmutable
    {
        return $this->reviewPublicDate;
    }

    public function setReviewPublicDate(DateTimeImmutable $reviewPublicDate): self
    {
        $this->reviewPublicDate = $reviewPublicDate;
        return $this;
    }
    #[ORM\PrePersist]
    public function setReviewPublicDateValue() : void
    {
        $this->reviewPublicDate = new DateTimeImmutable();
    }

    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(Recipe $recipe): self
    {
        $this->recipe = $recipe;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }





}
