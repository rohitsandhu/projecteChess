<?php

namespace App\Repository;

use App\Entity\Ronda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ronda|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ronda|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ronda[]    findAll()
 * @method Ronda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RondaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ronda::class);
    }

    // /**
    //  * @return Ronda[] Returns an array of Ronda objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ronda
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
