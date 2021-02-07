<?php

namespace App\Controller;

use App\Entity\Arbitre;
use App\Entity\ByesJugadorTorneig;
use App\Entity\Partida;
use App\Entity\Ronda;
use App\Entity\Torneig;
use App\Entity\Jugador;
use App\Form\TorneigType;
use App\Repository\ArbitreRepository;
use App\Repository\ByesJugadorTorneigRepository;
use App\Repository\JugadorRepository;
use App\Repository\PartidaRepository;
use App\Repository\RondaRepository;
use App\Repository\TorneigRepository;
use MongoDB\Driver\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Intl\Countries;

/**
 * @Route("/torneig")
 */
class TorneigController extends AbstractController
{
    /**
     * @Route("/", name="torneig_index", methods={"GET"})
     */
    public function index(TorneigRepository $torneigRepository, SessionInterface $session): Response
    {


        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }


        return $this->render('torneig/index.html.twig', [
            'torneigs' => $torneigRepository->findAll(),
        ]);
    }

    /**
     * @Route("/index", name="torneig_index_clean", methods={"GET"})
     */
    public function indexClean(TorneigRepository $torneigRepository, SessionInterface $session): Response
    {

        return $this->render('torneig/indexClean.html.twig', [
            'torneigs' => $torneigRepository->findAll(),
        ]);
    }

    /**
     * @Route("/tornejosJugador", name="tornejosJugador", methods={"GET", "POST"})
     */
    public function tornejosJugador(Request $request, TorneigRepository $torneigRepository, ArbitreRepository $ar): Response
    {


        $idJugador = intval($request->get('idJugador'));

        $tornejosJugador = $torneigRepository->trobarTornejosJugador($idJugador, $ar);
//        dd( $tornejosJugador);


        return $this->render('torneig/indexClean.html.twig', [
            'torneigs' => $tornejosJugador
        ]);
    }



    /**
     * @Route("/new", name="torneig_new", methods={"GET","POST"})
     */
    public function new(Request $request, TorneigRepository $tr, SessionInterface $session): Response
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        $torneig = new Torneig();
        $form = $this->createForm(TorneigType::class, $torneig);
        $form->handleRequest($request);
        $noExisteix = false;

        if ($form->isSubmitted()) {

            $xd = $tr->findOneBy(['nom' => $torneig->getNom()]);

            if ($xd == null) {
                $noExisteix = true;
                $torneig->setEstat("Esperant Jugadors");

            } else {
                $session->set("torneigNoCreat", "f");
            }
        }


        if ($form->isSubmitted() && $form->isValid() && $noExisteix) {

            $country = Countries::getName($torneig->getPais(), "ca");

            $torneig->setPais($country);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($torneig);
            $entityManager->flush();

            return $this->redirectToRoute('torneig_index');
        }

        return $this->render('torneig/new.html.twig', [
            'torneig' => $torneig,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="torneig_show", methods={"GET"})
     */
    public function show(Torneig $torneig, SessionInterface $session): Response
    {
        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        return $this->render('torneig/show.html.twig', [
            'torneig' => $torneig,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="torneig_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Torneig $torneig, SessionInterface $session): Response
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        $form = $this->createForm(TorneigType::class, $torneig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('torneig_index');
        }

        return $this->render('torneig/edit.html.twig', [
            'torneig' => $torneig,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="torneig_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Torneig $torneig, TorneigRepository $tr, SessionInterface $session): Response
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        if ($this->isCsrfTokenValid('delete' . $torneig->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();

            $tr->borrarTorneigItot($torneig->getId());

        }

        return $this->redirectToRoute('torneig_index');
    }

    /**
     * @Route("/{id}/llistarJugadors", name="llistarJugadors", methods={"GET","POST"})
     */
    public function llistarJugadors(Request $request, Torneig $torneig, JugadorRepository $jr, SessionInterface $session): Response
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        $totsElsJugadors = $jr->findAll();
        $jugadorsTorneig = $torneig->getLlistaJugadors();

        $newArray = [];

        foreach ($totsElsJugadors as $j) {
            $existeix = false;

            foreach ($jugadorsTorneig as $jt) {
                if ($jt->getId() == $j->getId()) {
                    $existeix = true;
                }

            }

            if (!$existeix) {
                array_push($newArray, $j);
            }
        }


        return $this->render('torneig/llistarJugadors.html.twig', [
            'torneig' => $torneig,
            'jugadorsNoTorneig' => $newArray,
            'jugadorsTorneig' => $jugadorsTorneig
        ]);
    }

    /**
     * @Route("/{id}/llistarJugadorsDintreTorneig", name="llistarJugadorsDintreTorneig", methods={"GET","POST"})
     */
    public function llistarJugadorsDintreTorneig(Request $request, Torneig $torneig, JugadorRepository $jr, SessionInterface $session): Response
    {

        
        $jugadorsTorneig = $torneig->getLlistaJugadors();


        return $this->render('torneig/llistarJugadorsClean.html.twig', [
            'torneig' => $torneig,
            'jugadorsTorneig' => $jugadorsTorneig
        ]);
    }

    /**
     * @Route("/addJugadorTorneig", name="addJugadorTorneig", methods={"GET","POST"})
     */
    public function addJugadorTorneig(Request $request, TorneigRepository $tr, JugadorRepository $jr, SessionInterface $session): Response
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $intIdJugador = intval($_POST['idJugador']);
        $intIdTorenig = intval($_POST['idTorneig']);
        $jugador = $jr->find($intIdJugador);
        $torneig = $tr->find($intIdTorenig);

        $torneig->getLlistaJugadors()->add($jugador);
        $entityManager->persist($torneig);
        $entityManager->flush();


        $totsElsJugadors = $jr->findAll();
        $jugadorsTorneig = $torneig->getLlistaJugadors();

        $newArray = [];

        foreach ($totsElsJugadors as $j) {
            $existeix = false;

            foreach ($jugadorsTorneig as $jt) {
                if ($jt->getId() == $j->getId()) {
                    $existeix = true;
                }

            }

            if (!$existeix) {
                array_push($newArray, $j);
            }
        }

        return $this->render('torneig/llistarJugadors.html.twig', [
            'torneig' => $torneig,
            'jugadorsNoTorneig' => $newArray,
            'jugadorsTorneig' => $jugadorsTorneig
        ]);

    }


    /**
     * @Route("/eliminarJugadorTorneig", name="eliminarJugadorTorneig", methods={"GET","POST"})
     */
    public function eliminarJugadorTorneig(Request $request, TorneigRepository $tr, JugadorRepository $jr, SessionInterface $s, SessionInterface $session): Response
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $intIdJugador = intval($_POST['idJugador']);
        $intIdTorenig = intval($_POST['idTorneig']);

        $jugador = $jr->find($intIdJugador);
        $torneig = $tr->find($intIdTorenig);


        for ($i = 0; $i < count($torneig->getLlistaJugadors()); $i++) {

            if ($torneig->getLlistaJugadors()->get($i) == $jugador) {

                $torneig->getLlistaJugadors()->remove($i);
            }
        }


        $entityManager->persist($torneig);
        $entityManager->flush();

        $totsElsJugadors = $jr->findAll();
        $jugadorsTorneig = $torneig->getLlistaJugadors();

        $newArray = [];

        foreach ($totsElsJugadors as $j) {
            $existeix = false;

            foreach ($jugadorsTorneig as $jt) {
                if ($jt->getId() == $j->getId()) {
                    $existeix = true;
                }
            }

            if (!$existeix) {
                array_push($newArray, $j);
            }
        }

        return $this->render('torneig/llistarJugadors.html.twig', [
            'torneig' => $torneig,
            'jugadorsNoTorneig' => $newArray,
            'jugadorsTorneig' => $jugadorsTorneig
        ]);
    }


    /**
     * @Route("/començar", name="començar", methods={"GET","POST"})
     */
    public function començar(Request $request, TorneigRepository $tr, JugadorRepository $jr, SessionInterface $s): Response
    {

        if ($s->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        $intIdTorenig = intval($_POST['idTorneig']);

        $torneig = $tr->find($intIdTorenig);
        $rondes = $torneig->getNumRondes();
        $numJugadors = count($torneig->getLlistaJugadors());

        $minRondes = log($numJugadors, 2);

        $fail = false;
        if ($rondes < $minRondes) {
            $fail = true;
            $s->set("ErrorsComençant1", "Hi ha massa poques rondes per els jugadors que hi han. (min Rondes = log^2 Jugadors)");
        }

        if ($rondes > ($numJugadors / 2)) {
            $fail = true;
            $s->set("ErrorsComençant2", "Hi ha masses rondes per els jugadors participants (max Rondes = jugadors/2 )");
        }


        if ($fail) {
            return $this->render('torneig/index.html.twig', [
                'torneig' => $torneig,
                'torneigs' => $tr->findAll()
            ]);
        }

        $arr = $torneig->getLlistaJugadors();

        $entityManager = $this->getDoctrine()->getManager();
        foreach ($arr as $j) {

            $bb = new ByesJugadorTorneig();
            $bb->setByes($torneig->getNumeroByes());
            $bb->setIdJugador($j);
            $bb->setPunts(0);
            $bb->setIdTorneig($torneig);
            $entityManager->persist($bb);
        }
        $entityManager->flush();


        $entityManager = $this->getDoctrine()->getManager();

        $torneig->setEstat("Preparar ronda");
        $entityManager->persist($torneig);
        $entityManager->flush();

        $s->set("començat", "xd");
        return $this->render('torneig/index.html.twig', [
//            'torneig' => $torneig,
            'torneigs' => $tr->findAll(),
        ]);

    }


    /**
     * @Route("/prepararRonda", name="prepararRonda" , methods={"GET","POST"})
     */
    public function prepararRonda(Request $request, TorneigRepository $tr, ByesJugadorTorneigRepository $bjtr, JugadorRepository $jr, SessionInterface $session): Response
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }

        $idTorneig = $request->get("idTorneig");

        $torneig = $tr->find($idTorneig);

        $arrayObjectes = $bjtr->findTotsElsJugadorsTorneig($torneig, $jr);


        usort($arrayObjectes, function ($a, $b) {
            return $b->getIdJugador()->getElo() - $a->getIdJugador()->getElo();
        });

        foreach ($torneig->getLlistaRondes() as $e) {
            foreach ($e->getLlistaPartides() as $p) {

//                dump($p);

            }


        }
//        dd();

        return $this->render('torneig/prepararRonda.html.twig', [
            'torneig' => $torneig,
            'arrayOrdenat' => $arrayObjectes,
        ]);
    }


    /**
     * @Route("/començarRonda", name="començarRonda" , methods={"GET","POST"})
     */
    public function començarRonda(Request $request, TorneigRepository $tr, ByesJugadorTorneigRepository $bjtr, JugadorRepository $jr, RondaRepository $rr, PartidaRepository $pr, SessionInterface $session): Response
    {


        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }

        $torneig = $tr->find($request->get("idTorneig"));


        $torneig->setEstat("Posar resultats");
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($torneig);
        $entityManager->flush();
        $array = $bjtr->findTotsElsJugadorsTorneig($torneig, $jr);

        $arrayJugadorsBye = [];
        $arrayJugadorsPlaying = [];

        $juga = true;

        $ids_jugadors_bye = $request->get('idXD', []);

        $arrayJugadorsBye = array_filter($array, function ($v) use ($ids_jugadors_bye) {
            return in_array($v->getIdJugador()->getId(), $ids_jugadors_bye);
        });


        $arrayJugadorsPlaying = array_filter($array, function ($v) use ($ids_jugadors_bye) {
            return !in_array($v->getIdJugador()->getId(), $ids_jugadors_bye);
        });

        foreach ($arrayJugadorsBye as $j) {
            $j->setByes($j->getByes() - 1);

            $temp = $bjtr->find($j->getId());
            $temp->setByes($temp->getByes() - 1);

            $entityManager->persist($temp);
        }

        $entityManager->flush();


        usort($arrayJugadorsPlaying, function ($a, $b) {
            return $b->getIdJugador()->getElo() - $a->getIdJugador()->getElo();
        });

        $rondaExisteix = $rr->findTotesLesRondesPerId($torneig->getId(), $tr, $jr, $pr);
        //  || count($rondaExisteix) <= 0
        if (empty($rondaExisteix)) {
            $ronda = $this->firstRound($torneig, $arrayJugadorsPlaying, $entityManager, $arrayJugadorsBye, $bjtr);

            return $this->render('torneig/ronda.html.twig', [
                'ronda' => $ronda,
            ]);
        } else {
            //dump($bjtr->findTotsElsJugadorsTorneig($torneig, $jr));
            $ronda = $this->goNext($torneig, $arrayJugadorsPlaying, $entityManager, $arrayJugadorsBye, $array, $jr);

            return $this->render('torneig/ronda.html.twig', [
                'ronda' => $ronda,
            ]);

        }


    }


    function goNext($torneig, $arrayJugadorsPlaying, $entityManager, $arrayJugadorsBye, $totsElsJugadordeBJT, $jr, SessionInterface $session)
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        usort($arrayJugadorsPlaying, function ($a, $b) {
            if ($b->getPunts() == $a->getPunts()) {
                return $b->getIdJugador()->getElo() - $a->getIdJugador()->getElo();
            }
            return $b->getPunts() - $a->getPunts();
        });

        $arrayJugadorsPlaying = array_map(function ($v) {
            $v->hasPair = false;
            return $v;
        }, $arrayJugadorsPlaying);

        $tmp_partides = [];
        $i = 0;
        // dd($arrayJugadorsPlaying);
        while ($i < count($arrayJugadorsPlaying) - 1) {
            if ($arrayJugadorsPlaying[$i]->hasPair) {
                $i++;
                continue;
            }

            $k = $i + 1;

            // $r = $this->enfrontamentRepetit($j1, $j2, $jr, $torneig);
            while (isset($arrayJugadorsPlaying[$k]) && $arrayJugadorsPlaying[$k]->hasPair) {
                $k++;
            }


            while (isset($arrayJugadorsPlaying[$k]) && ($this->enfrontamentRepetit(
                        $arrayJugadorsPlaying[$i]->getIdJugador(),
                        $arrayJugadorsPlaying[$k]->getIdJugador(),
                        $jr, $torneig) || $arrayJugadorsPlaying[$k]->hasPair)) {

                $k++;
            }

//            dump([
//                'i' => $i,
//                'k' => $k
//            ]);
            $kk = $k;
            ////PETA
            while (isset($arrayJugadorsPlaying[$kk]) && ($this->colorOk(
                        $arrayJugadorsPlaying[$i]->getIdJugador(),
                        $arrayJugadorsPlaying[$kk]->getIdJugador(),
                        $jr, $torneig)['check']
                    || $arrayJugadorsPlaying[$kk]->hasPair)) {
                $kk++;

                if (!isset($arrayJugadorsPlaying[$kk])) break;
            }

            if ($kk >= count($arrayJugadorsPlaying)) {
                $c1 = 'B';
                $c2 = 'N';
                if (rand(0, 1)) {
                    $c1 = 'N';
                    $c2 = 'B';
                }

                $tmp_arr = [
                    'j1' => $arrayJugadorsPlaying[$i]->getIdJugador(),
                    'j2' => null,
                    'color' => [
                        'color1' => $c1,
                        'color2' => $c2
                    ]
                ];

                if (isset($arrayJugadorsPlaying[$k])) {
                    $tmp_arr['j2'] = $arrayJugadorsPlaying[$k]->getIdJugador();
                    $arrayJugadorsPlaying[$k]->hasPair = true;
                }

            } else {

//    dump($kk);

                ////PETA
                $tmp_arr = [
                    'j1' => $arrayJugadorsPlaying[$i]->getIdJugador(),
                    'j2' => null,
                    'color' => $this->colorOk(
                        $arrayJugadorsPlaying[$i]->getIdJugador(),
                        $arrayJugadorsPlaying[$kk]->getIdJugador(),
                        $jr, $torneig)
                ];

                if (isset($arrayJugadorsPlaying[$kk])) {
                    $tmp_arr['j2'] = $arrayJugadorsPlaying[$kk]->getIdJugador();
                    $arrayJugadorsPlaying[$kk]->hasPair = true;

                }
            }


            $tmp_partides[] = $tmp_arr;

            $arrayJugadorsPlaying[$i]->hasPair = true;

            $i++;
        }

        $noParella = array_filter($arrayJugadorsPlaying, function ($v) {
            return !$v->hasPair;
        });
        $noParella = array_values($noParella);


        //        dd($noParella);
//
//        dd($totsElsJugadordeBJT);


//        dump($noParella);
//        dump($tmp_partides);


        $ronda = new Ronda();
        $ronda->setNumeroDeRonda(count($torneig->getLlistaRondes()) + 1);
        $ronda->setTorneig($torneig);
        $ronda->setEstestat("Posar resultats");

        $i = 1;
        foreach ($tmp_partides as $p) {
            $partida = new Partida();
            $partida->setNumeroTaula($i);


            if ($p['color']['color1'] == 'N' && $p['color']['color2'] == 'B') {

                $partida->setPecesBlanques($p['j1']);
                $partida->setPecesNegres($p['j2']);

            } elseif ($p['color']['color1'] == 'B' && $p['color']['color2'] == 'N') {

                $partida->setPecesBlanques($p['j2']);
                $partida->setPecesNegres($p['j1']);

            } elseif ($p['color']['color1'] == null && $p['color']['color2'] == null) {

                $partida->setPecesBlanques($p['j1']);
                $partida->setPecesNegres($p['j2']);

            } elseif ($p['color']['color1'] == "B" && $p['color']['color2'] == null) {

                $partida->setPecesBlanques($p['j2']);
                $partida->setPecesNegres($p['j1']);

            } elseif ($p['color']['color1'] == "N" && $p['color']['color2'] == null) {

                $partida->setPecesBlanques($p['j1']);
                $partida->setPecesNegres($p['j2']);

            } elseif ($p['color']['color1'] == null && $p['color']['color2'] == "B") {

                $partida->setPecesBlanques($p['j1']);
                $partida->setPecesNegres($p['j2']);


            } elseif ($p['color']['color1'] == null && $p['color']['color2'] == "N") {

                $partida->setPecesBlanques($p['j2']);
                $partida->setPecesNegres($p['j1']);

            }


            if (empty($partida->getPecesBlanques())) {

                $partida->setPecesBlanques($partida->getPecesNegres());

            }


            if ($partida->getPecesNegres() == null) {
                $partida->setResultat("Bye no voluntari");
                $partida->setPecesNegres(null);
                $partida->setPuntsNegres(0);
                $partida->setPuntsBlanques(1);
                $partida->setBye(true);

            } else {
                $partida->setPuntsNegres(0);
                $partida->setPuntsBlanques(0);
                $partida->setBye(false);
            }
            $partida->setRonda($ronda);

            $entityManager->persist($partida);
            $ronda->getLlistaPartides()->add($partida);

            $i++;
        }


//        dd($arrayJugadorsBye);

        $iterador = count($arrayJugadorsPlaying) + 1;


        foreach ($arrayJugadorsBye as $e) {

            $partida = new Partida();
            $partida->setRonda($ronda);
            $partida->setNumeroTaula(count($tmp_partides) + $iterador);
            $partida->setPecesBlanques($e->getIdJugador());
            $partida->setBye(true);
            $partida->setResultat("Bye voluntari");
            $partida->setPuntsBlanques(0);
            $partida->setPuntsNegres(0);

            $entityManager->persist($partida);
            $ronda->getLlistaPartides()->add($partida);

            $iterador++;
        }

        if (!empty($noParella)) {
            $partida = new Partida();
            $partida->setNumeroTaula(count($tmp_partides) + count($arrayJugadorsBye) + 1);
            $partida->setBye(true);
            $partida->setPecesBlanques($noParella[0]->getIdJugador());
            $partida->setPuntsNegres(0);
            $partida->setPuntsBlanques(1);
            $partida->setResultat("Bye no voluntari");
            $partida->setRonda($ronda);
            $entityManager->persist($partida);
            $ronda->getLlistaPartides()->add($partida);
        }


        $entityManager->persist($ronda);

        $entityManager->flush();

        return $ronda;
    }

    function firstRound($torneig, $arrayJugadorsPlaying, $entityManager, $arrayJugadorsBye, ByesJugadorTorneigRepository $bjtr, SessionInterface $session)
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        $numJugadors = count($arrayJugadorsPlaying);

        $torneig->setEstat("Posar resultats");
        $entityManager->persist($torneig);
        $entityManager->flush();

        $ronda = new Ronda();
        $ronda->setTorneig($torneig);
        $ronda->setEstestat("Posar resultats");
        $ronda->setNumeroDeRonda(1);


        $arrayPM = [];
        $arrayUM = [];

        $meitat = $numJugadors / 2;

        if ($numJugadors % 2 != 0) {
            $meitat -= 1;
        }

        for ($i = 0; $i < count($arrayJugadorsPlaying); $i++) {

            if ($i < $meitat) {
                array_push($arrayPM, $arrayJugadorsPlaying[$i]);
            } else {
                array_push($arrayUM, $arrayJugadorsPlaying[$i]);
            }
        }


        for ($i = 0; $i < count($arrayPM); $i++) {
            $partida = new Partida();
            $partida->setRonda($ronda);
            $partida->setNumeroTaula($i);
            $partida->setBye(false);
            $partida->setPuntsBlanques(0);
            $partida->setPuntsNegres(0);
            $random = random_int(0, 1);


            if ($random == 1) {
                $partida->setPecesBlanques($arrayPM[$i]->getIdJugador());
                $partida->setPecesNegres($arrayUM[$i]->getIdJugador());
            } else {
                $partida->setPecesBlanques($arrayUM[$i]->getIdJugador());
                $partida->setPecesNegres($arrayPM[$i]->getIdJugador());
            }


            $ronda->getLlistaPartides()->add($partida);
            $entityManager->persist($partida);
        }


        if ($numJugadors % 2 != 0) {
            $num = count($arrayUM) - 1;

            $ultimJugador = $arrayUM[$num];
            $partida = new Partida();
            $partida->setNumeroTaula(count($arrayPM) + 1);
            $partida->setRonda($ronda);
            $partida->setPecesBlanques($ultimJugador->getIdJugador());
            $partida->setBye(true);
            $partida->setPuntsBlanques(1);
            $partida->setPuntsNegres(0);
            $partida->setResultat("Bye no voluntari");
            $ronda->getLlistaPartides()->add($partida);

            $bjtr->sumarPunt($ultimJugador->getIdJugador()->getId(), $torneig->getId());


            $entityManager->persist($partida);
        }
//        $i = 0; $i < count($arrayJugadorsBye); $i++

        $xdIterador = count($arrayUM) + 1;
        foreach ($arrayJugadorsBye as $j) {

            $partida = new Partida();
            $partida->setRonda($ronda);
            $partida->setNumeroTaula($xdIterador);
            $partida->setPecesBlanques($j->getIdJugador());
            $partida->setBye(true);
            $partida->setResultat("Bye voluntari");
            $partida->setPuntsBlanques(0);
            $partida->setPuntsNegres(0);
            $ronda->getLlistaPartides()->add($partida);

            $entityManager->persist($partida);
            $xdIterador++;
        }

        $entityManager->persist($ronda);
        $entityManager->flush();

        return $ronda;
    }


    /**
     * @Route("/getRondaResults", name="getRondaResults" , methods={"GET","POST"})
     */
    public function getRondaResults(Request $request, TorneigRepository $tr, ByesJugadorTorneigRepository $bjtr, JugadorRepository $jr, RondaRepository $rr, PartidaRepository $pr, SessionInterface $session): Response
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }

        $torneig = $tr->find($request->get("idTorneig"));


        $ronda = $rr->findTotesLesRondesPerId($torneig->getId(), $tr, $jr, $pr);


        return $this->render('torneig/ronda.html.twig', [
            'ronda' => $ronda,
        ]);
    }

    /**
     * @Route("/guardarPuntuacio", name="guardarPuntuacio" , methods={"GET","POST"})
     */
    public function guardarPuntuacio(SessionInterface $s, Request $request, TorneigRepository $tr, SessionInterface $session, ByesJugadorTorneigRepository $bjtr, JugadorRepository $jr, RondaRepository $rr, PartidaRepository $pr): Response
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }


        $torneig = $tr->find($request->get("idTorneig"));


        $idBlanques = $request->get("idBlanques");
        $idNegres = $request->get("idNegres");
        $puntsBlanques = floatval($request->get("puntsBlanques"));
        $puntsNegres = floatval($request->get("puntsNegres"));
        $idPartida = intval($request->get("idPartida"));

        if (($puntsBlanques == 1 || $puntsBlanques == 0.5 || $puntsBlanques == 0) && ($puntsNegres == 1 || $puntsNegres == 0.5 || $puntsNegres == 0) && (($puntsBlanques + $puntsNegres) == 1)) {


            $partida = $pr->find($idPartida);

            $partida->setPuntsBlanques($puntsBlanques);
            $partida->setPuntsNegres($puntsNegres);
            $partida->setResultat("Posat");


            $bjtr->setPunts($partida);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($partida);
            $entityManager->flush();


            $ronda = $rr->findTotesLesRondesPerId($torneig->getId(), $tr, $jr, $pr);


            return $this->render('torneig/ronda.html.twig', [
                'ronda' => $ronda,
                'resultatOk' => "tezt",
            ]);

        } else {

            $ronda = $rr->findTotesLesRondesPerId($torneig->getId(), $tr, $jr, $pr);


            return $this->render('torneig/ronda.html.twig', [
                'ronda' => $ronda,
                'errorNumeros' => "tezt",
            ]);
        }
    }


    /**
     * @Route("/acabarRonda", name="acabarRonda" , methods={"GET","POST"})
     */
    public function acabarRonda(Request $request, SessionInterface $session,TorneigRepository $tr, ByesJugadorTorneigRepository $bjtr, JugadorRepository $jr, RondaRepository $rr, PartidaRepository $pr): Response
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $id = intval($request->get("idRonda"));
        $idTorneig = intval($request->get("idTorneig"));
        $torneig = $tr->find($idTorneig);
        // dump($id);

        $xd = $rr->mirarSiEstaAcabat($id);

        $ronda = $rr->findTotesLesRondesPerId($torneig->getId(), $tr, $jr, $pr);

        if ($xd) {


            $rr->canviarEstat($ronda, "acabada");


            if ($ronda->getNumeroDeRonda() == $torneig->getNumRondes()) {

                $torneig->setEstat("acabat");

                $entityManager->persist($torneig);
                $entityManager->flush();

                return $this->render('torneig/resultatTorneig.html.twig', [
                    'torneig' => $torneig,
                ]);
            }


            // $torneig->getLlistaRondes()->add($ronda);


            $torneig->setEstat("Preparar ronda");

            //$entityManager->persist($torneig);


            $arrayObjectes = $bjtr->findTotsElsJugadorsTorneig($torneig, $jr);


            usort($arrayObjectes, function ($a, $b) {
                return $b->getIdJugador()->getElo() - $a->getIdJugador()->getElo();
            });

            $entityManager->flush();

            return $this->render('torneig/prepararRonda.html.twig', [
                'torneig' => $torneig,
                'arrayOrdenat' => $arrayObjectes,
            ]);


        } else {

            return $this->render('torneig/ronda.html.twig', [
                'ronda' => $ronda,
                'rondaNoAcabada' => 'tezt',
            ]);

        }
    }


    function enfrontamentRepetit(Jugador $j1, Jugador $j2, JugadorRepository $jr, Torneig $torneig)
    {
        return !empty($jr->comprobarEnfrontamentAnterior($j1, $j2, $torneig));
    }

    function colorOk(Jugador $j1, Jugador $j2, JugadorRepository $jr, Torneig $torneig)
    {
        $p1 = $jr->partidaAnterior($j1, $torneig);
        $p2 = $jr->partidaAnterior($j2, $torneig);

        if (empty($p1))
            $c1 = null;
        else if ($p1[0]['peces_blanques_id'] == $j1->getId())
            $c1 = 'B';
        else
            $c1 = 'N';

        if (empty($p2))
            $c2 = null;
        else if ($p2[0]['peces_blanques_id'] == $j2->getId())
            $c2 = 'B';
        else
            $c2 = 'N';

        return [
            'check' => $c1 == $c2 && $c2 != null && $c1 != null,
            'color1' => $c1,
            'color2' => $c2,
        ];
    }


    /**
     * @Route("/resultatTorneig", name="resultatTorneig" , methods={"GET","POST"})
     */
    public function resultatTorneig(Request $request, TorneigRepository $tr, ByesJugadorTorneigRepository $bjtr, JugadorRepository $jr): Response
    {



        $idTorneig = intval($request->get("idTorneig"));
        $torneig = $tr->find($idTorneig);

        $arrayjugadors = $bjtr->findTotsElsJugadorsTorneig2($torneig, $jr);



//        dd($arrayjugadors);

        return $this->render('torneig/resultatTorneig.html.twig', [
            'jugadors' => $arrayjugadors,
            'torneig' => $torneig,
        ]);
    }
}



/*
    - Declarar les rondes abans de començar el torneig
    - 2 jugadors no es poden enfrentar més 1 cop.
    - Si es inpar un jugador reb un bye(no li desconta dels seus), no conta com que hagi jugat com a ningun color.
    - en general els jugadors estan parellat amb jugadors amb els mateixos punts
    - Ningun jugadors ha de jugar amb el mateix color més de 2 cops seguits
        - en general s'ha de jugar els mateixos cops amb cada color
        - en general s'ha de anar alternant el color cada ronda.

    mateixa persona
    mateix color
    diferents punts


    jugador pot estar a més de una grup a la vegada
*\
    // } else {


//            // quan es inparell \\
//
//
////            dd($arrayPM,$arrayUM);
//
//            $rondaExisteix = $rr->findTotesLesRondesPerId($torneig->getId(), $tr, $jr, $pr);
//
//
//            if (count($rondaExisteix) <= 0) {
//
//                $meitat = $numJugadors / 2;
//
//                for ($i = 0; $i < count($arrayJugadorsPlaying); $i++) {
//
//                    if ($i < ($meitat - 1)) {
//                        array_push($arrayPM, $arrayJugadorsPlaying[$i]);
//                    } else {
//                        array_push($arrayUM, $arrayJugadorsPlaying[$i]);
//                    }
//                }
//                echo "no hi han rondes en aquest torneig i es inparell";
//
//                $ronda = new Ronda();
//                $ronda->setEstestat("Posar resultats");
//                $ronda->setTorneig($torneig);
//                $ronda->setNumeroDeRonda(1);
//
//                $arrayPartides = [];
//
//                for ($i = 0; $i < count($arrayPM); $i++) {
//                    $partida = new Partida();
//                    $partida->setRonda($ronda);
//                    $partida->setNumeroTaula($i);
//                    $partida->setBye(false);
//                    $partida->setPuntsBlanques(0);
//                    $partida->setPuntsNegres(0);
//                    $random = random_int(0, 1);
//
//                    if ($random == 1) {
//                        $partida->setPecesBlanques($arrayPM[$i]->getIdJugador());
//                        $partida->setPecesNegres($arrayUM[$i]->getIdJugador());
//                    } else {
//                        $partida->setPecesBlanques($arrayUM[$i]->getIdJugador());
//                        $partida->setPecesNegres($arrayPM[$i]->getIdJugador());
//                    }
//
//                    array_push($arrayPartides, $partida);
//                    $ronda->getLlistaPartides()->add($partida);
//                    $entityManager->persist($partida);
//                }
//
//                $num = count($arrayUM) - 1;
//
//                $ultimJugador = $arrayUM[$num];
//                $partida = new Partida();
//                $partida->setNumeroTaula(count($arrayPM) + 1);
//                $partida->setRonda($ronda);
//                $partida->setPecesBlanques($ultimJugador->getIdJugador());
//                $partida->setBye(true);
//                $partida->setPuntsBlanques(0);
//                $partida->setPuntsNegres(0);
//                $partida->setResultat("Bye no voluntari");
//                $ronda->getLlistaPartides()->add($partida);
//                array_push($arrayPartides, $partida);
//                $entityManager->persist($partida);
//
//                for ($i = 0; $i < count($arrayJugadorsBye); $i++) {
//                    $partida = new Partida();
//                    $partida->setNumeroTaula(count($ronda->getLlistaPartides()) + 1);
//                    $partida->setRonda($ronda);
//                    $partida->setPecesBlanques($arrayJugadorsBye[$i]->getIdJugador());
//                    $partida->setBye(true);
//                    $partida->setPuntsBlanques(0);
//                    $partida->setPuntsNegres(0);
//                    $partida->setResultat("Bye voluntari");
//                    $ronda->getLlistaPartides()->add($partida);
//                    array_push($arrayPartides, $partida);
//                    $entityManager->persist($partida);
//                }
//
//
//                $entityManager->persist($ronda);
//                $entityManager->flush();
//
////                foreach ($ronda->getLlistaPartides() as $p){
////                    echo $p->getPecesBlanques()->getId()." ".$p->getPecesNegres()->getId()."<br>";
////                }
////
////                dd();
//                return $this->render('torneig/ronda.html.twig', [
//                    'ronda' => $ronda,
//                ]);
//            } else {
//
//
//
//
//
//
//            }
        // }