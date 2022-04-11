<?php


namespace App\Controller;

use App\Entity\Maisonh;
use App\Form\MaisonType;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaisonController extends AbstractController
{
    /**
     * @Route("/Admin", name="app_maison")
     */
    public function index(): Response
    {
        $maison = $this->getDoctrine()->getManager()->getRepository(Maisonh::class)->findAll();
        return $this->render('maison/index.html.twig', [
            'm'=>$maison
        ]);
    }

    /**
     * @Route("/FrontMaison", name="app_maisonF")
     */
    public function indexFrontMaison(): Response
    {
        $maison = $this->getDoctrine()->getManager()->getRepository(Maisonh::class)->findAll();
        return $this->render('maisonFront/index.html.twig', [
            'm'=>$maison
        ]);
    }
    /**
     * @Route("/addMaison", name="addMaison")
     */
    public function addMaison(Request $request): Response
    {
        $maison = new Maisonh();
        $form = $this->createForm(MaisonType::class,$maison);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($maison);
            $em->flush();

            return $this->redirectToRoute('app_maison');

        }
        return $this->render('maison/ajouterMaison.html.twig',['f'=>$form->createView()]);
    }

    /**
     * @Route("/removemaison/{id}", name="supp_maison")
     */
    public function supprimerMaison(Maisonh $maison): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($maison);
        $em->flush();
        return $this->redirectToRoute('app_maison');
    }

    /**
     * @Route("/modifMaison/{id}", name="modifMaison")
     */
    public function modifierMaison(Request $request,$id): Response
    {
        $maison= $this->getDoctrine()->getManager()->getRepository(Maisonh::class)->find($id);
        $form = $this->createForm(MaisonType::class,$maison);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_maison');
        }
        return $this->render('maison/updateMaison.html.twig',['f'=>$form->createView()]);
    }
}