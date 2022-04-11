<?php

namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoyageController extends AbstractController
{
    /**
     * @Route("/Admin", name="app_voyage")
     */
    public function index(): Response
    {
        $voyage = $this->getDoctrine()->getManager()->getRepository(Voyage::class)->findAll();
        return $this->render('voyage/index.html.twig', [
            'v'=>$voyage
        ]);
    }

    /**
     * @Route("/voyageFront", name="app_voyageF")
     */
    public function voyageFront(): Response
    {
        $voyage = $this->getDoctrine()->getManager()->getRepository(Voyage::class)->findAll();
        return $this->render('voyageFront/index.html.twig', [
            'v'=>$voyage
        ]);
    }
    /**
     * @Route("/addVoyage", name="addVoyage")
     */
    public function addVoyage(Request $request): Response
    {
        $voyage = new Voyage();
        $form = $this->createForm(VoyageType::class,$voyage);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($voyage);
            $em->flush();

            return $this->redirectToRoute('app_voyage');

        }
        return $this->render('voyage/ajouterVoyage.html.twig',['f'=>$form->createView()]);
    }

    /**
     * @Route("/removevoyage/{id}", name="supp_voyage")
     */
    public function supprimerVoyage(Voyage $voyage): Response
    {
      $em=$this->getDoctrine()->getManager();
      $em->remove($voyage);
      $em->flush();
        return $this->redirectToRoute('app_voyage');
    }

    /**
     * @Route("/modifVoyage/{id}", name="modifVoyage")
     */
    public function modifierVoyage(Request $request,$id): Response
    {
       $voyage= $this->getDoctrine()->getManager()->getRepository(Voyage::class)->find($id);
        $form = $this->createForm(VoyageType::class,$voyage);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_voyage');
        }
        return $this->render('voyage/updateVoyage.html.twig',['f'=>$form->createView()]);
    }
}
