<?php

namespace App\Controller;

use App\Entity\Reponsereclamation;
use App\Form\ReponsereclamationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reponsereclamation")
 */
class ReponsereclamationController extends AbstractController
{
    /**
     * @Route("/", name="app_reponsereclamation_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reponsereclamations = $entityManager
            ->getRepository(Reponsereclamation::class)
            ->findAll();

        return $this->render('reponsereclamation/index.html.twig', [
            'reponsereclamations' => $reponsereclamations,
        ]);
    }

    /**
     * @Route("/new", name="app_reponsereclamation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reponsereclamation = new Reponsereclamation();
        $form = $this->createForm(ReponsereclamationType::class, $reponsereclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($reponsereclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reponsereclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponsereclamation/new.html.twig', [
            'reponsereclamation' => $reponsereclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idrep}", name="app_reponsereclamation_show", methods={"GET"})
     */
    public function show(Reponsereclamation $reponsereclamation): Response
    {
        return $this->render('reponsereclamation/show.html.twig', [
            'reponsereclamation' => $reponsereclamation,
        ]);
    }

    /**
     * @Route("/{idrep}/edit", name="app_reponsereclamation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reponsereclamation $reponsereclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponsereclamationType::class, $reponsereclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponsereclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponsereclamation/edit.html.twig', [
            'reponsereclamation' => $reponsereclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idrep}", name="app_reponsereclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reponsereclamation $reponsereclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponsereclamation->getIdrep(), $request->request->get('_token'))) {
            $entityManager->remove($reponsereclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponsereclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}
