<?php

namespace App\Controller;

use App\Entity\Reservationm;
use App\Form\ReservationmType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservationm")
 */
class ReservationmController extends AbstractController
{
    /**
     * @Route("/", name="app_reservationm_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reservationms = $entityManager
            ->getRepository(Reservationm::class)
            ->findAll();

        return $this->render('reservationm/index.html.twig', [
            'reservationms' => $reservationms,
        ]);
    }

    /**
     * @Route("/new", name="app_reservationm_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationm = new Reservationm();
        $form = $this->createForm(ReservationmType::class, $reservationm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationm);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationm_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservationm/new.html.twig', [
            'reservationm' => $reservationm,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idRes}", name="app_reservationm_show", methods={"GET"})
     */
    public function show(Reservationm $reservationm): Response
    {
        return $this->render('reservationm/show.html.twig', [
            'reservationm' => $reservationm,
        ]);
    }

    /**
     * @Route("/{idRes}/edit", name="app_reservationm_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reservationm $reservationm, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationmType::class, $reservationm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationm_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservationm/edit.html.twig', [
            'reservationm' => $reservationm,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idRes}", name="app_reservationm_delete", methods={"POST"})
     */
    public function delete(Request $request, Reservationm $reservationm, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationm->getIdRes(), $request->request->get('_token'))) {
            $entityManager->remove($reservationm);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservationm_index', [], Response::HTTP_SEE_OTHER);
    }
}
