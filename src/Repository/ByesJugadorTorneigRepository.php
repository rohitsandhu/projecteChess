<?php

namespace App\Repository;

use App\Entity\ByesJugadorTorneig;
use App\Entity\Partida;
use App\Entity\Torneig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ByesJugadorTorneig|null find($id, $lockMode = null, $lockVersion = null)
 * @method ByesJugadorTorneig|null findOneBy(array $criteria, array $orderBy = null)
 * @method ByesJugadorTorneig[]    findAll()
 * @method ByesJugadorTorneig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ByesJugadorTorneigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ByesJugadorTorneig::class);
    }

    // /**
    //  * @return ByesJugadorTorneig[] Returns an array of ByesJugadorTorneig objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ByesJugadorTorneig
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findTotsElsJugadorsTorneig(Torneig $torneig, JugadorRepository $jr): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM byes_jugador_torneig where id_torneig_id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $torneig->getId()]);

        $xd = $stmt->fetchAllAssociative();


        $arrayObjectes = [];


        for ($i = 0; $i < count($xd); $i++) {
            $asdfg = new ByesJugadorTorneig();

            $asdfg->setIdTorneig($torneig)
                ->setIdJugador($jr->find($xd[$i]['id_jugador_id']))
                ->setByes(intval($xd[$i]['byes']))
                ->setId(intval($xd[$i]['id']));
                $asdfg->setPunts(floatval($xd[$i]['punts']));

            array_push($arrayObjectes, $asdfg);
        }

        return $arrayObjectes;
    }

    public function findTotsElsJugadorsTorneig2(Torneig $torneig, JugadorRepository $jr): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT byes_jugador_torneig.* FROM byes_jugador_torneig, jugador where id_torneig_id = :id and id_jugador_id=jugador.id order by punts desc, elo desc';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $torneig->getId()]);

        $xd = $stmt->fetchAllAssociative();


        $arrayObjectes = [];


        for ($i = 0; $i < count($xd); $i++) {
            $asdfg = new ByesJugadorTorneig();

            $asdfg->setIdTorneig($torneig)
                ->setIdJugador($jr->find($xd[$i]['id_jugador_id']))
                ->setByes(intval($xd[$i]['byes']))
                ->setId(intval($xd[$i]['id']));
            $asdfg->setPunts(floatval($xd[$i]['punts']));

            array_push($arrayObjectes, $asdfg);
        }

        return $arrayObjectes;
    }

    public function setPunts(Partida $partida)
    {
        $conn = $this->getEntityManager()->getConnection();


        $sql = 'Update byes_jugador_torneig set punts= (punts+ :punts) where id_jugador_id = :id_jugador AND id_torneig_id= :id_torneig ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['punts' => $partida->getPuntsBlanques(),
            'id_jugador' => $partida->getPecesBlanques()->getId(),
            'id_torneig' => $partida->getRonda()->getTorneig()->getId(),
        ]);


        if ($partida->getPecesNegres() != null) {

            $sql = 'Update byes_jugador_torneig set punts= (punts+ :punts) where id_jugador_id = :id_jugador AND id_torneig_id= :id_torneig ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['punts' => $partida->getPuntsNegres(),
                'id_jugador' => $partida->getPecesNegres()->getId(),
                'id_torneig' => $partida->getRonda()->getTorneig()->getId(),
            ]);

        }

    }

    public function sumarPunt(int $idJugador, int $idTorneig)
    {
        $conn = $this->getEntityManager()->getConnection();


        $sql = 'Update byes_jugador_torneig set punts= (punts+ 1) where id_jugador_id = :id_j AND id_torneig_id= :id_t';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id_j' => $idJugador,
            'id_t' =>$idTorneig,
        ]);

    }


}
