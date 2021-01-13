<?php

namespace App\Controller;

use App\Entity\Jugador;
use App\Form\JugadorType;
use App\Repository\JugadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/jugador")
 */
class JugadorController extends AbstractController
{
    /**
     * @Route("/", name="jugador_index", methods={"GET"})
     */
    public function index(JugadorRepository $jugadorRepository): Response
    {
        return $this->render('jugador/index.html.twig', [
            'jugadors' => $jugadorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="jugador_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $jugador = new Jugador();
        $form = $this->createForm(JugadorType::class, $jugador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jugador);
            $entityManager->flush();

            return $this->redirectToRoute('jugador_index');
        }

        return $this->render('jugador/new.html.twig', [
            'jugador' => $jugador,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="jugador_show", methods={"GET"})
     */
    public function show(Jugador $jugador): Response
    {
        return $this->render('jugador/show.html.twig', [
            'jugador' => $jugador,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="jugador_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Jugador $jugador): Response
    {
        $form = $this->createForm(JugadorType::class, $jugador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('jugador_index');
        }

        return $this->render('jugador/edit.html.twig', [
            'jugador' => $jugador,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="jugador_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Jugador $jugador): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jugador->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($jugador);
            $entityManager->flush();
        }

        return $this->redirectToRoute('jugador_index');
    }
}
