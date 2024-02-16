<?php

namespace App\Model\RecipeManager;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints\NotBlank;
class PublishRecipeModel
{
      #[NotBlank]
      private DateTimeImmutable $date;

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): void
    {
        $this->date = $date;

    }

}
