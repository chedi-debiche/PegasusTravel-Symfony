<?php

namespace App\Controller;

use App\Entity\Reservationv;
use App\Form\ReservationvType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservationv")
 */
class ReservationvController extends AbstractController
{
    /**
     * @Route("/", name="app_reservationv_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reservationvs = $entityManager
            ->getRepository(Reservationv::class)
            ->findAll();

        return $this->render('reservationv/index.html.twig', [
            'reservationvs' => $reservationvs,
        ]);
    }

    /**
     * @Route("/new", name="app_reservationv_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationv = new Reservationv();
        $form = $this->createForm(ReservationvType::class, $reservationv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationv);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservationv/new.html.twig', [
            'reservationv' => $reservationv,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idr}", name="app_reservationv_show", methods={"GET"})
     */
    public function show(Reservationv $reservationv): Response
    {
        return $this->render('reservationv/show.html.twig', [
            'reservationv' => $reservationv,
        ]);
    }

    /**
     * @Route("/{idr}/edit", name="app_reservationv_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reservationv $reservationv, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationvType::class, $reservationv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservationv/edit.html.twig', [
            'reservationv' => $reservationv,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idr}", name="app_reservationv_delete", methods={"POST"})
     */
    public function delete(Request $request, Reservationv $reservationv, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationv->getIdr(), $request->request->get('_token'))) {
            $entityManager->remove($reservationv);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservationv_index', [], Response::HTTP_SEE_OTHER);
    }
}
