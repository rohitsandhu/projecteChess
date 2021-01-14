<?php

namespace App\Controller;

use App\Entity\Ronda;
use App\Form\RondaType;
use App\Repository\RondaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ronda")
 */
class RondaController extends AbstractController
{
    /**
     * @Route("/", name="ronda_index", methods={"GET"})
     */
    public function index(RondaRepository $rondaRepository): Response
    {
        return $this->render('ronda/index.html.twig', [
            'rondas' => $rondaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ronda_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ronda = new Ronda();
        $form = $this->createForm(RondaType::class, $ronda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ronda);
            $entityManager->flush();

            return $this->redirectToRoute('ronda_index');
        }

        return $this->render('ronda/new.html.twig', [
            'ronda' => $ronda,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ronda_show", methods={"GET"})
     */
    public function show(Ronda $ronda): Response
    {
        return $this->render('ronda/show.html.twig', [
            'ronda' => $ronda,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ronda_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ronda $ronda): Response
    {
        $form = $this->createForm(RondaType::class, $ronda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ronda_index');
        }

        return $this->render('ronda/edit.html.twig', [
            'ronda' => $ronda,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ronda_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ronda $ronda): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ronda->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ronda);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ronda_index');
    }
}
