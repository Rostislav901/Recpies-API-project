<?php

namespace App\Tests\Service\RecipeMangerServices;

use App\Entity\Recipe;
use App\Entity\User;
use App\Model\PrivateCooker;
use App\Model\RecipeItem;
use App\Repository\RecipeRepository;
use App\Service\RecipeMangerServices\GetRecipeService;
use App\Utils\Mapper\CookerModelMapper;
use App\Utils\Mapper\RecipeModelMapper;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use App\Model\Recipe as RecipeModel;

class GetRecipeServiceTest extends TestCase
{
    private Security $security;
    private RecipeRepository $recipeRepository;
    private RecipeModelMapper $recipeModelMapper;
    private CookerModelMapper $cookerModelMapper;
    private User $user;

    protected function setUp(): void
    {

        parent::setUp();

        $this->security = $this->createMock(Security::class);
        $this->recipeRepository = $this->createMock(RecipeRepository::class);
        $this->recipeModelMapper = $this->createMock(RecipeModelMapper::class);
        $this->cookerModelMapper = $this->createMock(CookerModelMapper::class);
        $this->user = new User();
        $this->security->expects($this->once())
                ->method("getUser")
                ->willReturn($this->user);
    }

    public function testGetRecipes()
    {
        $recipes = [(new Recipe())->setTitle("test")];

        $this->recipeRepository->expects($this->once())
                ->method("getRecipesByUser")
                ->with($this->user)
                ->willReturn($recipes);

        $this->recipeModelMapper->expects($this->once())
                ->method("mapRecipeShortForm")
                ->with((new Recipe())->setTitle("test"))
                ->willReturn((new RecipeItem())->setRating(5)->setImage("testImage"));

        $actual = (new GetRecipeService($this->security,$this->recipeRepository,$this->recipeModelMapper,$this->cookerModelMapper))
                        ->getRecipes();

        $this->assertEquals(RecipeModel::class,get_class($actual));
        $this->assertEquals(RecipeItem::class,get_class($actual->getRecipes()[0]));
        $this->assertCount(1,$actual->getRecipes());
        $this->assertEquals(5,$actual->getRecipes()[0]->getRating());
        $this->assertEquals("testImage",$actual->getRecipes()[0]->getImage());
    }

    public function testGetCookerInfo()
    {
         $this->cookerModelMapper->expects($this->once())
             ->method("mapPrivateCooker")
             ->with($this->user)
             ->willReturn((new PrivateCooker())->setFullName("Test Test")->setCountry("Test"));

         $actual =  (new GetRecipeService($this->security,$this->recipeRepository,$this->recipeModelMapper,$this->cookerModelMapper))
                        ->getCookerInfo();

         $this->assertEquals(PrivateCooker::class,get_class($actual));
         $this->assertEquals("Test Test",$actual->getFullName());
         $this->assertEquals("Test",$actual->getCountry());
    }
}
