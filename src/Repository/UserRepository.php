<?php

namespace App\Repository;

use App\Entity\User;
use App\Exceptions\UserNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function existsByEmail(string $email) : bool
    {
          $user = $this->findOneBy(["email"=>$email]);
          return  !($user === null);
    }


    public function getById(int $id) : User
    {
        $user = $this->find($id);
        return $user === null ? throw new UserNotFoundException() : $user;
    }


    public function getFullNameById(int $id) : string
    {
        $user = $this->find($id);
        if ($user === null)
            throw new UserNotFoundException();

        return  $user->getFirstName() . " " . $user->getLastName();
    }

    public function saveUser(UserInterface $user) : void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

}
