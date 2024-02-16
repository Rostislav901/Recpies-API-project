<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
      public const RATATOUILLE_RECIPE_REFERENCE = "Ratatouille";
      public const PASTA_RECIPE_REFERENCE = "Shrimp Scampi with Pasta";
      public const BRUSCHETTA_RECIPE_REFERENCE = "Balsamic Bruschetta";
      public function load(ObjectManager $manager)
      {
          $ratatouille = (new Recipe())
                            ->setCuisine($this->getReference(CuisineFixtures::FRENCH_CUISINE_REFERENCE))
                            ->setUser($this->getReference(UserFixtures::HECTOR_JIMENEZ_BRAVO_REFERENCE))
                            ->setTitle("Ratatouille")
                            ->setSlug("/ratatouille/")
                            ->setDescription("Ratatouille is a French Provencal dish that consists of stewed vegetables.Though recipes and ingredients vary&there are some ingredients that are almost always used:eggplant & tomatoes & zucchini & onions & and bell peppers.")
                            ->setImage("ratatouille.png")
                            ->setIngredients([
                                                "2 tablespoons olive oil & divided","3 cloves garlic & minced",
                                                "1 eggplant & cut into 1/2 inch cubes", "2 teaspoons dried parsley",
                                                "salt to taste","1 cup grated Parmesan cheese",
                                                "2 zucchini& sliced","2 large tomatoes& chopped",
                                                "2 cups sliced fresh mushrooms","1 large onion& sliced into rings",
                                                "1 green or red bell pepper& sliced"])
                            ->setSteps([
                                "Preheat the oven to 350 degrees F (175 degrees C). Coat the bottom and sides of a 1 1/2-quart casserole dish with 1 tablespoon olive oil.",
                                "Heat remaining 1 tablespoon olive oil in a medium skillet over medium heat. Cook and stir garlic until fragrant and golden brown. Add eggplant and parsley; cook and stir until eggplant is tender and soft& about 10 minutes. Season with salt to taste.",
                                "Spread eggplant mixture evenly across the bottom of the prepared casserole dish; sprinkle with a few tablespoons of Parmesan cheese. Spread zucchini in an even layer over top. Lightly salt and sprinkle with a little more cheese. Continue layering in this fashion& with tomatoes& mushrooms& onion& and bell pepper& covering each layer with a sprinkling of salt and cheese.",
                                "Bake in preheated oven until vegetables are tender& about 45 minutes."
                            ])
                            ->setImages(["img1","img2","img3"]);

          $pasta = (new Recipe())
                                ->setCuisine($this->getReference(CuisineFixtures::ITALIAN_CUISINE_REFERENCE))
                                ->setUser($this->getReference(UserFixtures::GORDON_RAMSEY_REFERENCE))
                                ->setTitle("Shrimp Scampi with Pasta")
                                ->setSlug("/pasta/")
                                ->setDescription("Shrimp scampi is a seafood dish made of shrimp cooked in a butter& garlic& and white wine sauce.
                                                You can serve shrimp scampi by itself as an appetizer or over pasta as a main dish.")

                                ->setImage("pasta.png")
                                ->setIngredients([
                                    "1 (16 ounce) package linguine pasta","2 tablespoons butter","2 tablespoons extra-virgin olive oil",
                                    "2 shallots& finely diced","2 cloves garlic& minced","1 pinch red pepper flakes (Optional)",
                                    "1 pound shrimp& peeled and deveined","1 pinch kosher salt and freshly ground pepper",
                                    "½ cup dry white wine","1 lemon& juiced","2 tablespoons butter","2 tablespoons extra-virgin olive oil",
                                    "¼ cup finely chopped fresh parsley leaves","1 teaspoon extra-virgin olive oil& or to taste"
                                ])
                                ->setSteps(["Gather ingredients.","Bring a large pot of salted water to a boil; cook linguine
                                             in boiling water until nearly tender& 6 to 8 minutes. Drain.", "Melt 2 tablespoons butter with 2 tablespoons olive oil in a large skillet over medium heat.",
                                             "Cook and stir shallots& garlic& and red pepper flakes in the hot butter and oil until shallots are translucent& 3 to 4 minutes.",
                                             "Season shrimp with kosher salt and black pepper; add to the skillet and cook until pink& stirring occasionally& 2 to 3 minutes. Remove shrimp from skillet and keep warm.",
                                             "Pour white wine and lemon juice into skillet and bring to a boil while scraping the browned bits of food off of the bottom of the skillet with a wooden spoon.",
                                             "Melt 2 tablespoons butter in skillet& stir 2 tablespoons olive oil into butter mixture& and bring to a simmer.","Toss linguine& shrimp& and parsley in the butter mixture until coated; season with salt and black pepper. Drizzle with 1 teaspoon olive oil to serve.",
                                             "Serve hot and enjoy!"
                                            ])
                                ->setImages(["img1","img2","img3"]);

          $bruschetta = (new Recipe())
                                ->setCuisine($this->getReference(CuisineFixtures::ITALIAN_CUISINE_REFERENCE))
                                ->setUser($this->getReference(UserFixtures::JAMIE_OLIVER_REFERENCE))
                                ->setTitle("Balsamic Bruschetta")
                                ->setSlug("/balsamic Bruschetta/")
                                ->setDescription("Bruschetta (pronounced brew-SKET-tah) is an Italian appetizer or antipasti that starts with a base of toasted or grilled bread. ")
                                ->setImage("breschetta.img")
                                ->setIngredients([
                                            " loaf French bread& cut into 1/4-inch slices","1 tablespoon extra-virgin olive oil",
                                            "8 roma (plum) tomatoes& diced", "⅓ cup chopped fresh basil","1 ounce Parmesan cheese& freshly grated",
                                            "2 cloves garlic& minced", "1 tablespoon good quality balsamic vinegar","2 teaspoons extra-virgin olive oil",
                                            "¼ teaspoon kosher salt", "¼ teaspoon freshly ground black pepper"
                                ])
                                ->setSteps([
                                        "Gather all ingredients.","Preheat oven to 400 degrees F (200 degrees C).",
                                        "Brush bread slices on both sides lightly with 1 tablespoon oil and place on large baking sheet. Toast bread until golden& 5 to 10 minutes& turning halfway through.",
                                        "Meanwhile& toss together tomatoes& basil& Parmesan cheese& and garlic in a bowl.","Mix in balsamic vinegar& 2 teaspoons olive oil& kosher salt& and pepper.",
                                        "Spoon tomato mixture onto toasted bread slices.","Serve immediately and enjoy!"
                                ])
                                ->setImages(["img"]);

          $manager->persist($ratatouille);
          $manager->persist($pasta);
          $manager->persist($bruschetta);

          $manager->flush();

          $recipes = [
                    self::RATATOUILLE_RECIPE_REFERENCE => $ratatouille,
                    self::PASTA_RECIPE_REFERENCE => $pasta,
                    self::BRUSCHETTA_RECIPE_REFERENCE => $bruschetta
          ];

          foreach ($recipes as $reference => $recipe)
          {
              $this->addReference($reference,$recipe);
          }

      }
      public function getDependencies() : array
      {
          return [
            CuisineFixtures::class,
            UserFixtures::class
          ];
      }
}
