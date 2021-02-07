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
    public function nomExistex($getNom)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.nom = :val')
            ->setParameter('val', $getNom)
            ->getQuery()
            ->getOneOrNullResult();
    }


//    public function findArrayJugadorOrdenatsPerElo(int $id): array
//    {
//        $conn = $this->getEntityManager()->getConnection();
//
//        $sql = 'SELECT jugador.* FROM jugador, torneig_jugador where jugador.id = torneig_jugador.jugador_id and torneig_jugador.torneig_id = :id ORDER BY elo desc';
//        $stmt = $conn->prepare($sql);
//        $stmt->execute(['id' => $id]);
//
//        // returns an array of arrays (i.e. a raw data set)
//        return $stmt->fetchAllAssociative();
//    }
    public function borrarTorneigItot(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM ronda where  torneig_id= :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        $resultat = $stmt->fetchAllAssociative();

        foreach ($resultat as $r) {

            $sql = 'Delete FROM partida where  ronda_id= :id';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['id' => $r['id']]);
        }


        $sql = 'Delete FROM byes_jugador_torneig where  id_torneig_id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);


        $sql = 'Delete FROM torneig_jugador where  torneig_id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);


        $sql = 'Delete FROM ronda where  torneig_id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        $sql = 'Delete FROM torneig where  id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

    }

    public function trobarTornejosJugador(int $idJugador, ArbitreRepository $ar)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT DISTINCT torneig.* FROM torneig, ronda, partida 
                    where torneig.id=ronda.torneig_id AND ronda.id=partida.ronda_id AND 
                          (partida.peces_blanques_id=:id or partida.peces_negres_id=:id)';

        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $idJugador]);


        $resultat = $stmt->fetchAllAssociative();

        $arrayReturn = [];


        foreach ($resultat as $t){

            $torneig = new Torneig();
            $torneig->setEstat($t['estat']);
            $torneig->setPais($t['pais']);
            $torneig->setNom($t['nom']);
            $torneig->setArbitre($ar->find($t['arbitre_id']));
            $torneig->setNumeroByes(intval($t['numero_byes']));
            $torneig->setNumRondes(intval($t['num_rondes']));
            $torneig->setId($t['id']);


            array_push($arrayReturn, $torneig);
        }


        return $arrayReturn;





    }
}
