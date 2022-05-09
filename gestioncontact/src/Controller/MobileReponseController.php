<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponsereclamation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Request;
class MobileReponseController extends AbstractController
{


    /**
     * @Route("/displayMobileReponse", name="displayMobileReponse")
     */
    public function displayMobileReponse( SerializerInterface $serializer): Response
    {

        $rep = $this->getDoctrine()->getRepository(Reponsereclamation::class);
        $reclamation=$rep->findAll();

        $formatted = $serializer->normalize($reclamation,'json',['groups' => 'post:read']);
        return $this->render('reponsereclamation/MobileJSON.html.twig',['data'=>$formatted]);
        return new Response(json_encode($formatted)) ;
    }
    /**
     * @Route("/addMobileReponse", name="MobileReponse")
     */
    public function addMobileReponse(Request $request, SerializerInterface $serializer): Response
    {

        $typeR = new Reponsereclamation();


        $entityManager = $this->getDoctrine()->getManager();

        $typeR->setNumero($request->query->get("numero")) ;

        $typeR->setReponse($request->get('reponse')) ;
        $entityManager->persist($typeR);
        $entityManager->flush();
        $formatted = $serializer->normalize($typeR,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }

    /**
     * @Route("/deleteMobileReponse", name="deleteMobileReponse")
     */
    public function deleteMobileReponse(Request $request, SerializerInterface $serializer): Response
    {
        $idrep=$request->query->get("idrep") ;
        $entityManager = $this->getDoctrine()->getManager();
        $reclamation = $entityManager->getRepository(Reponsereclamation::class)->find( $idrep);
        if($reclamation!=null){
            $entityManager->remove($reclamation);
            $entityManager->flush();
            $formatted = $serializer->normalize($reclamation,'json',['groups' => 'post:read']);
            return new Response(json_encode($formatted)) ;

        }
        return new Response(" type invalide") ;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface

     * @Route("/updateMobileReponse", name="updateMobileReponse")

     */

    public function updateMobileReponse(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $typeR = $this->getDoctrine()->getManager()
            ->getRepository(Reponsereclamation::class)
            ->find($request->get("idrep"));
        $typeR->setReponse($request->get('reponse')) ;

        $em->persist($typeR);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($typeR);
        return new JsonResponse("Reponse Reclamation a ete modifiee avec success.");
    }

}
