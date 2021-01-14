<?php

namespace App\Repository;

use App\Entity\Torneig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Torneig|null find($id, $lockMode = null, $lockVersion = null)
 * @method Torneig|null findOneBy(array $criteria, array $orderBy = null)
 * @method Torneig[]    findAll()
 * @method Torneig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TorneigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Torneig::class);
    }

    // /**
    //  * @return Torneig[] Returns an array of Torneig objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Torneig
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
