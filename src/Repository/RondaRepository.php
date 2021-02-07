<?php

namespace App\Repository;

use App\Entity\Jugador;
use App\Entity\Partida;
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
    public function findTotesLesRondesPerId(int $id, TorneigRepository $tr, JugadorRepository $jr, PartidaRepository $pr)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM ronda where torneig_id = :id order by numero_de_ronda DESC';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);


        $res = $stmt->fetchAllAssociative();

        if (empty($res)) {
            return $res;
        } else {


            $ronda = new Ronda();
            $ronda->setId(intval($res[0]['id']));
            $ronda->setNumeroDeRonda($res[0]['numero_de_ronda']);
            $ronda->setTorneig($tr->find($res[0]['torneig_id']));
            $ronda->setEstestat($res[0]['estestat']);


            $partides = $pr->trobarPartesPerIdRonda($ronda->getId());


            if (count($partides) <= 0) {

            } else {

                foreach ($partides as $p) {

                    $partida = new Partida();
                    $partida->setId($p['id']);
                    $partida->setPuntsNegres($p['punts_negres']);
                    $partida->setPuntsBlanques($p['punts_blanques']);
                    $partida->setNumeroTaula($p['numero_taula']);
                    $partida->setBye($p['bye']);
                    $partida->setResultat($p['resultat']);
                    $partida->setRonda($ronda);


                    if ($p['peces_blanques_id'] == null) {

                    } else {
                        $j = $jr->find($p['peces_blanques_id']);
                        $partida->setPecesBlanques($j);
                    }


                    if ($p['peces_negres_id'] == null) {

                    } else {
                        $j = $jr->find($p['peces_negres_id']);
                        $partida->setPecesNegres($j);
                    }


                    $ronda->getLlistaPartides()->add($partida);


                }
            }


            return $ronda;
        }
    }

    public function mirarSiEstaAcabat(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM partida where ronda_id = :id and resultat is null ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)

        $res = $stmt->fetchAllAssociative();


        if (count($res) > 0) {
            return false;
        } else {

            return true;
        }


    }

    public function canviarEstat(Ronda $ronda, string $string)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'Update ronda set estestat=:str where id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'id' => $ronda->getId(),
            'str' => $string
        ]);
    }


}













