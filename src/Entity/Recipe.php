<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(type: "string",length: 255)]
    private string $title;
    #[ORM\Column(type: "string",length: 1024,nullable: true)]
    private string $description;
    #[ORM\Column(type: "string",length: 1024)]
    private string $image;
    #[ORM\Column(type: Types::DATE_IMMUTABLE,nullable: true)]
    private ?DateTimeImmutable $publicationDate;
    #[ORM\Column(type: "string",length: 255)]
    private string $slug;
    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: Cuisine::class,cascade: ["persist"])]
    private ?Cuisine $cuisine ;
    #[ORM\OneToMany(mappedBy: "recipe",targetEntity: Review::class)]
    private ?Collection $reviews;
    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $ingredients;
    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $steps;
    #[ORM\Column(type: Types::SIMPLE_ARRAY,nullable: true)]
    private ?array $images = null;
    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: User::class, cascade: ["persist"])]
    private UserInterface $user;
    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser() : UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getPublicationDate(): ?DateTimeImmutable
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(?DateTimeImmutable $publicationDate): static
    {
        $this->publicationDate = $publicationDate;
        return $this;
    }
    #[ORM\PrePersist]
    public function setPublicationDatePrePersist() : void
    {
        $this->publicationDate = new DateTimeImmutable();
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getCuisine(): ?Cuisine
    {
        return $this->cuisine;
    }

    public function setCuisine(Cuisine|int|null $cuisine): static
    {
        $this->cuisine = $cuisine;
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


    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): self
    {
        $this->images = $images;
        return $this;
    }

    /**
     * @return Collection<Review>|null
     */
    public function getReviews(): ?Collection
    {
        return $this->reviews;
    }

    /**
     * @param Collection<Review>|null $reviews
     * @return Recipe
     */
    public function setReviews(?Collection $reviews): self
    {
        $this->reviews = $reviews;
        return $this;
    }

    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    public function setIngredients(array $ingredients): self
    {
        $this->ingredients = $ingredients;
        return $this;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function setSteps(array $steps): self
    {
        $this->steps = $steps;
        return $this;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }




}
