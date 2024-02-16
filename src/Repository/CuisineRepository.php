<?php

namespace App\Repository;

use App\Entity\Cuisine;
use App\Exceptions\CuisineNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cuisine>
 *
 * @method Cuisine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cuisine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cuisine[]    findAll()
 * @method Cuisine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CuisineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cuisine::class);
    }



    public function existById(int $id)  : bool
    {
        return  null !== $this->find($id);
    }

    /**
     * @return Cuisine[]
     */
    public function findByTitleAsc() : array
    {
          return  $this->findBy([],["title"=>Criteria::ASC]);
    }
}

