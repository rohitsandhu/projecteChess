<?php

namespace App\Repository;

use App\Entity\Jugador;
use App\Entity\Torneig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Jugador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jugador|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jugador[]    findAll()
 * @method Jugador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JugadorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jugador::class);
    }

    // /**
    //  * @return Jugador[] Returns an array of Jugador objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Jugador
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function comprobarEnfrontamentAnterior(Jugador $j1, Jugador $j2, Torneig $torneig)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM partida 
                left join ronda on ronda.id=partida.ronda_id
            where 
                ronda.torneig_id = :id_torneig
                and (
                    (peces_blanques_id = :id_j1 and peces_negres_id = :id_j2) 
                    or (peces_blanques_id = :id_j2 and peces_negres_id = :id_j1)
                )';
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'id_torneig' => $torneig->getId(),
            'id_j1' => $j1->getId(),
            'id_j2' => $j2->getId()
        ]);

        return $stmt->fetchAllAssociative();
    }

    public function partidaAnterior(Jugador $j1, Torneig $torneig)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT
            *
        FROM
            partida
        LEFT JOIN ronda ON ronda.id = partida.ronda_id
        WHERE
            ronda.torneig_id = :id_torneig AND(
                peces_blanques_id = :id_j1  or peces_negres_id = :id_j1 
            )
            and bye = 0 
            and partida.ronda_id = (
                SELECT
                    max(partida.ronda_id)
                FROM
                    partida
                LEFT JOIN ronda ON ronda.id = partida.ronda_id
                WHERE
                    ronda.torneig_id = :id_torneig AND(
                        peces_blanques_id = :id_j1  or peces_negres_id = :id_j1 
                    )
                    and bye = 0 
            )";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'id_torneig' => $torneig->getId(),
            'id_j1' => $j1->getId(),
        ]);



        return $stmt->fetchAllAssociative();


    }

}
