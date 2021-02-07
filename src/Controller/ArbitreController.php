<?php

namespace App\Controller;

use App\Entity\Arbitre;
use App\Entity\Torneig;
use App\Form\ArbitreType;
use App\Repository\ArbitreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Node\Expression\Test\SameasTest;

/**
 * @Route("/arbitre")
 */
class ArbitreController extends AbstractController
{
    /**
     * @Route("/", name="arbitre_index", methods={"GET"})
     */
    public function index(ArbitreRepository $arbitreRepository,  SessionInterface $session): Response
    {
        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        return $this->render('arbitre/index.html.twig', [
            'arbitres' => $arbitreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="arbitre_new", methods={"GET","POST"})
     */
    public function new(Request $request,  SessionInterface $session): Response
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        $arbitre = new Arbitre();
        $form = $this->createForm(ArbitreType::class, $arbitre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($arbitre);
            $entityManager->flush();

            return $this->redirectToRoute('arbitre_index');
        }

        return $this->render('arbitre/new.html.twig', [
            'arbitre' => $arbitre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="arbitre_show", methods={"GET"})
     */
    public function show(Arbitre $arbitre,  SessionInterface $session): Response
    {
        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        return $this->render('arbitre/show.html.twig', [
            'arbitre' => $arbitre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="arbitre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Arbitre $arbitre, SessionInterface $session): Response
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        $form = $this->createForm(ArbitreType::class, $arbitre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('arbitre_index');
        }

        return $this->render('arbitre/edit.html.twig', [
            'arbitre' => $arbitre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="arbitre_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Arbitre $arbitre, SessionInterface $session): Response
    {

        if ($session->get('arbitreLogged') ==null){
            return $this->render('index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        if ($this->isCsrfTokenValid('delete' . $arbitre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($arbitre);
            $entityManager->flush();
        }
        return $this->redirectToRoute('arbitre_index');
    }




}




