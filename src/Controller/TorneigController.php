<?php

namespace App\Controller;

use App\Entity\Torneig;
use App\Form\TorneigType;
use App\Repository\TorneigRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/torneig")
 */
class TorneigController extends AbstractController
{
    /**
     * @Route("/", name="torneig_index", methods={"GET"})
     */
    public function index(TorneigRepository $torneigRepository): Response
    {
        return $this->render('torneig/index.html.twig', [
            'torneigs' => $torneigRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="torneig_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $torneig = new Torneig();
        $form = $this->createForm(TorneigType::class, $torneig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
    public function show(Torneig $torneig): Response
    {
        return $this->render('torneig/show.html.twig', [
            'torneig' => $torneig,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="torneig_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Torneig $torneig): Response
    {
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
    public function delete(Request $request, Torneig $torneig): Response
    {
        if ($this->isCsrfTokenValid('delete'.$torneig->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($torneig);
            $entityManager->flush();
        }

        return $this->redirectToRoute('torneig_index');
    }
}
