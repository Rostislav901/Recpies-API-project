<?php

namespace App\Model;

class Recipe
{
    /**
     * @var RecipeItem[]$recipes
     */

      private array $recipes = [];

    /**
     * @param  RecipeItem[]$recipes
     */
      public function setRecipes(array $recipes): static
      {
          $this->recipes = $recipes;
          return $this;
      }

    /**
     * @return RecipeItem[]
     */
      public function getRecipes() : array
      {
          return  $this->recipes;
      }
}
