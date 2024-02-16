<?php

namespace App\Entity;

use App\Repository\NewsletterRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: NewsletterRepository::class)]
class NewsletterClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(type: "string",length: 255)]
    private string $email;
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $subscribeDate;
    #[ORM\PrePersist]
    public function setSubscribeDateValue() : void
    {
        $this->subscribeDate = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getSubscribeDate(): DateTimeImmutable
    {
        return $this->subscribeDate;
    }

    public function setSubscribeDate(DateTimeImmutable $subscribeDate): static
    {
        $this->subscribeDate = $subscribeDate;
        return  $this;
    }



}
