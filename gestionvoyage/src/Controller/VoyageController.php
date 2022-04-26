<?php

namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use MercurySeries\FlashyBundle\FlashyNotifier;

class VoyageController extends AbstractController
{
    /**
     * @Route("/Admin", name="app_voyage")
     */
    public function index(): Response
    {
        $voyage = $this->getDoctrine()->getManager()->getRepository(Voyage::class)->findAll();

        return $this->redirectToRoute('Recherchevoyage',array('v' => $voyage));
       // return $this->render('voyage/index.html.twig', [
         //   'v' => $voyage
      //  ]);
    }

    /**
     * @Route("/voyageFront", name="app_voyageF")
     */
    public function voyageFront(Request $request,PaginatorInterface $paginator): Response
    {
        $voyage = $this->getDoctrine()->getManager()->getRepository(Voyage::class)->findAll();
        $voyage = $paginator->paginate(
            $voyage, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2/*limit per page*/
        );
        return $this->render('voyageFront/index.html.twig', [
            'v' => $voyage
        ]);
    }

    /**
     * @Route("/addVoyage", name="addVoyage")
     */
    public function addVoyage(Request $request): Response
    {
        $voyage = new Voyage();
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $voyage->getImage();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('uploads_directory'),$filename);
            $voyage->setImage($filename);
            $em = $this->getDoctrine()->getManager();
            $em->persist($voyage);
            $em->flush();

            return $this->redirectToRoute('app_voyage');

        }

        return $this->render('voyage/ajouterVoyage.html.twig', ['f' => $form->createView()]);
    }

    /**
     * @Route("/removevoyage/{id}", name="supp_voyage")
     */
    public function supprimerVoyage(Voyage $voyage): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($voyage);
        $em->flush();
        return $this->redirectToRoute('app_voyage');
    }

    /**
     * @Route("/modifVoyage/{id}", name="modifVoyage")
     */
    public function modifierVoyage(Request $request, $id): Response
    {
        $voyage = $this->getDoctrine()->getManager()->getRepository(Voyage::class)->find($id);
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $voyage->getImage();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('uploads_directory'),$filename);
            $voyage->setImage($filename);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_voyage');
        }
        return $this->render('voyage/updateVoyage.html.twig', ['f' => $form->createView()]);
    }

    /**
     * @Route("/statvoyage", name="statvoyage")
     */
    public function stat()
    {

        $repository = $this->getDoctrine()->getRepository(Voyage::class);
        $voyage = $repository->findAll();

        $em = $this->getDoctrine()->getManager();


        $pr1 = 0;
        $pr2 = 0;



        foreach ($voyage as $voyage) {
            if ($voyage->getPrix() == "900")  :

                $pr1 += 1;
            else:

                $pr2 += 1;


            endif;

        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['prix', 'nom'],
                ['900', $pr1],
                ['1200', $pr2],

            ]
        );
        $pieChart->getOptions()->setTitle('Prix des voyages');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('voyage/statVoyage.html.twig', array('piechart' => $pieChart));
    }
    /**
     * @Route("/trivoyagePrix", name="trivoyagePrix")
     */
    public function Tri(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $query = $em->createQuery(
            'SELECT n FROM App\Entity\Voyage n
            ORDER BY n.prix '
        );

        $voyage = $query->getResult();



        return $this->render('voyage/index.html.twig',
            array('v' => $voyage));

    }
    /**
     * @Route("/trivoyageNom", name="trivoyageNom")
     */
    public function Triv(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $query = $em->createQuery(
            'SELECT n FROM App\Entity\Voyage n
            ORDER BY n.nom '
        );

        $voyage = $query->getResult();



        return $this->render('voyage/index.html.twig',
            array('v' => $voyage));

    }




    /**
     * @Route("/imprimvoyage", name="imprimvoyage")
     */
    public function imprimvoyage(): Response

    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);


        $voyage = $this->getDoctrine()->getManager()->getRepository(Voyage::class)->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('voyage/imprimvoyage.html.twig', [
            'v' => $voyage,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Liste Voyage.pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * @Route("/Recherchevoyage", name="Recherchevoyage")
     */
    public function rechercheByName(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $voyage=$em->getRepository(Voyage::class)->findAll();
        if($request->isMethod("POST"))
        {
            $prix = $request->get('prix');
            $voyage=$em->getRepository(Voyage::class)->findBy(array('prix'=>$prix));
        }
        return $this->render('voyage/index.html.twig', array('v' => $voyage));
        //    return $this->redirectToRoute('Recherche',array('m' => $maison));


    }

}
