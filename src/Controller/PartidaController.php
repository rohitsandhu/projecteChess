<?php

namespace App\Controller;

use App\Entity\Partida;
use App\Form\PartidaType;
use App\Repository\PartidaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/partida")
 */
class PartidaController extends AbstractController
{
    /**
     * @Route("/", name="partida_index", methods={"GET"})
     */
    public function index(PartidaRepository $partidaRepository, SessionInterface $session): Response
    {
        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        return $this->render('partida/index.html.twig', [
            'partidas' => $partidaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="partida_new", methods={"GET","POST"})
     */
    public function new(Request $request, SessionInterface $session): Response
    {
        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        $partida = new Partida();
        $form = $this->createForm(PartidaType::class, $partida);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($partida);
            $entityManager->flush();

            return $this->redirectToRoute('partida_index');
        }

        return $this->render('partida/new.html.twig', [
            'partida' => $partida,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="partida_show", methods={"GET"})
     */
    public function show(Partida $partida, SessionInterface $session): Response
    {
        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        return $this->render('partida/show.html.twig', [
            'partida' => $partida,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="partida_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Partida $partida, SessionInterface $session): Response
    {
        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        $form = $this->createForm(PartidaType::class, $partida);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('partida_index');
        }

        return $this->render('partida/edit.html.twig', [
            'partida' => $partida,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="partida_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Partida $partida, SessionInterface $session): Response
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        if ($this->isCsrfTokenValid('delete'.$partida->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($partida);
            $entityManager->flush();
        }

        return $this->redirectToRoute('partida_index');
    }
}
