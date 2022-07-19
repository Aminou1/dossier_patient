<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\PrestationRepository;
use App\Form\PrestationType;
use App\Entity\Prestation;
use Symfony\Component\HttpFoundation\Request;
use App\Constante\Constantes;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypePrestationRepository;

class PrestationController extends AbstractController
{

    private $PrestationRepository;
    private $TypePrestationRepository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager,TypePrestationRepository $TypePrestationRepository)
    {
        $this->entityManager = $entityManager;
      //  $this->PrestationRepository = $PrestationRepository;
        $this->TypePrestationRepository = $TypePrestationRepository;
    }


    /**
     * @Route("/prestation", name="prestation")
     */
    public function AddPrestation(Request $request, TypePrestationRepository $TypePrestationRepository): Response
    {
        $prestation = new Prestation();
        $resultat = "";
        $classe = "";
        $form = $this->createForm(PrestationType::class, $prestation);
         if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                $data = $request->request->get($form->getName());
                $prestationTypeId = $data['prestationtype'];
                $prestation_entity = $TypePrestationRepository->findOneBy(['id' => $prestationTypeId]);
                $prestation->setPrestationtype($prestation_entity);
                $this->entityManager->persist($prestation);
                $this->entityManager->flush();
             
             if ($prestation) {
                 return $this->redirectToRoute('liste_prestation');
             } else {
                 $resultat = "Echec de la creation!.";
                 $classe = "alert alert-danger";
                 return $this->render('prestation/prestation-form.html.twig', [
                    'action' => 'create',
                    'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
                 ]);
                 }
            }
             return $this->render('prestation/prestation-form.html.twig', [
                'controller_name' => 'PrestationController',
             'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
            ]);
           
        }


        /**
         * @Route("/listeprestation", name="liste_prestation")
         */
        public function listePrestation(): Response
        {
            // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
            $prestation = $this->getDoctrine()->getRepository(Prestation::class)->findBy([]);
            return $this->render('prestation/liste-prestation.html.twig', [
                'prestation' => $prestation
            ]);
            
        }

        /**
         * @Route("/{id}/supprimerprestation", name="delete_prestation", methods={"GET","POST"})
         */
        public function deletPrestation(Request $request, Prestation $prestation): Response
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($prestation);
            $entityManager->flush();
            return $this->redirectToRoute('liste_prestation');
        }

        /**
         * @Route("/{id}/editprestation", name="edit_prestation", methods={"GET","POST"})
         */
        public function editPrestation(Request $request, Prestation $prestation): Response
        {
            $form = $this->createForm(PrestationType::class, $prestation);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('liste_prestation');
            }
            $resultat = "";
            $classe = "";
            return $this->render('prestation/prestation-form.html.twig', [
                'prestattion' => $prestation,
                'action' => 'edit',
                'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe                ]);
        }
}
