<?php

namespace App\Repository;

use App\Entity\NewsletterClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NewsletterClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsletterClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsletterClient[]    findAll()
 * @method NewsletterClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsletterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsletterClient::class);
    }

    public function existsByEmail(string $email) : bool
    {
        return !(null === $this->findOneBy(["email"=>$email]));
    }

    public function saveSubscribe(NewsletterClient $newsletter)
    {
        $this->_em->persist($newsletter);
        $this->_em->flush();
    }
}


