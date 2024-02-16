<?php

namespace App\Repository;

use App\Entity\Review;
use App\Exceptions\ChangeReviewAccessException;
use App\Exceptions\RecipeNotFoundException;
use App\Exceptions\ReviewNotFoundException;
use App\Exceptions\UserReviewNotExistsException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private RecipeRepository $recipeRepository)
    {
        parent::__construct($registry, Review::class);
    }

    public function getCountByRecipeId(int $id) : ?int
    {
         return $this->count(["recipe"=>$id]);

    }

    public function getRating(int $id) : int
    {
         return  $this->_em->createQuery("SELECT SUM(r.rating) FROM App\Entity\Review r 
                                               WHERE r.recipe = :id")->setParameter("id",$id)->getSingleScalarResult();
    }

    public function getAllByRecipeId(int $id) : array
    {
        $recipe = $this->recipeRepository->getPublicRecipeById($id);

        $reviews = $this->findBy(["recipe"=>$recipe->getId()]);

        if($reviews === [])
            throw new ReviewNotFoundException();

        return $reviews;
    }

    public function existById(int $id) : bool
    {
        $review = $this->find($id);
        return  null !== $review;
    }

    public function getUserReviewById(int $id,UserInterface $user) : Review
    {

         if ($this->existById($id)) {

             $result = $this->findOneBy(["id" => $id, "user" => $user]);

             return $result === null ? throw new ChangeReviewAccessException() : $result;
         }
         throw new ReviewNotFoundException();

    }
    public function saveReview(Review $review) : void
    {
        $this->_em->persist($review);
        $this->_em->flush();
    }

    public function deleteReview(Review $review) : void
    {
        $this->_em->remove($review);
        $this->_em->flush();
    }

}
