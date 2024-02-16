<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Exceptions\CuisineNotFoundException;
use App\Exceptions\RecipeNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,private CuisineRepository $cuisineRepository)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @return Recipe[]
     */
    public function getRecipesByCuisineId(int $id) : array
    {
        if($this->cuisineRepository->existById($id) === false)
        {
            throw new CuisineNotFoundException();
        }
        $query = $this->_em->createQuery("SELECT r FROM App\Entity\Recipe r WHERE
                                                r.cuisine = :cuisineId AND r.publicationDate IS NOT NULL ");
        $query->setParameter("cuisineId",$id);
        $result =   $query->getResult();
        if($result === [])
        {
            throw new RecipeNotFoundException();
        }
        return  $result;
    }


    public function getPublicRecipeById(int $id) : Recipe
    {
        $recipe = $this->find($id);

        if(null === $recipe || $recipe->getPublicationDate() === null) throw new RecipeNotFoundException();

        return $recipe;
    }

    public function findByUserId(int $id, UserInterface $user) : Recipe
    {
        $recipe =  $this->findOneBy(["id"=>$id,"user"=>$user]);
         if(null === $recipe)
         {
             throw  new RecipeNotFoundException();
         }
         return  $recipe;
    }

    public function getRecipesByUser(?UserInterface $user) : array
    {
        $res =  $this->findBy(["user"=>$user]);

        return $res === [] ? throw  new RecipeNotFoundException(): $res;

    }

    public function get10Recipe () : array
    {

        $query = $this->_em->createQuery("SELECT r FROM App\Entity\Recipe r WHERE
                                                 r.publicationDate IS NOT NULL ");
        $recipes =   $query->getResult();

        shuffle($recipes);

        $result =  array_slice($recipes, 0, 10);
        if($result === []){
            throw new RecipeNotFoundException();
        }
        return  $result;
    }


    /**
     * @param UserInterface $user
     * @return Recipe[]
     */
    public function getPublicRecipesByUser(UserInterface $user) : array
    {
        $dql = "SELECT r FROM App\Entity\Recipe r 
                  WHERE r.user = :user AND r.publicationDate IS NOT NULL";
        $query =  $this->_em->createQuery($dql)->setParameter("user",$user);

        return $query->getResult();
    }

    public function getCountByUser(UserInterface $user) : int
    {
        return  $this->count(["user"=>$user]);
    }

    public function getPrivateCountByUser(UserInterface $user) : int
    {
        return  $this->count(["user"=>$user,"publicationDate"=>null]);
    }
    public function getPublicCountByUser(UserInterface $user) : int
    {
        $dql = "SELECT COUNT(r.id) 
            FROM App\Entity\Recipe r 
            WHERE r.user = :user 
            AND r.publicationDate IS NOT NULL";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('user', $user);

        return (int)$query->getSingleScalarResult();
    }

    public function saveRecipe(Recipe $recipe) : void
    {
        $this->_em->persist($recipe);
        $this->_em->flush();
    }

    public function deleteRecipe(Recipe $recipe) : void
    {
        $this->_em->remove($recipe);
        $this->_em->flush();

    }


}
