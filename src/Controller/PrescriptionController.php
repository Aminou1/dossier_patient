<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PrescriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Constante\Constantes;
use Symfony\Component\Security\Core\Security;
use App\Repository\PrescriptionRepository;
use App\Entity\Prescription;
use App\Entity\Structure;
use App\Repository\StructureRepository;
use App\Repository\DossierRepository;
use App\Repository\TypePrescriptionRepository;



class PrescriptionController extends AbstractController
{

    private $PrescriptionRepository;
    private $entityManager;
    private $TypePrescriptionRepository;
    private $StructureRepository;
    private $DossierRepository;

    public function __construct(EntityManagerInterface $entityManager,PrescriptionRepository $PrescriptionRepository
                                ,StructureRepository $StructureRepository,DossierRepository $DossierRepository,
                                TypePrescriptionRepository $TypePrescriptionRepository)
    {
        $this->entityManager = $entityManager;
        $this->PrescriptionRepository = $PrescriptionRepository;
        $this-> TypePrescriptionRepository = $TypePrescriptionRepository;
        $this-> StructureRepository = $StructureRepository;
        $this-> DossierRepository = $DossierRepository;
    }



       /**
         * @Route("/listeprescription", name="listes_prescription")
         */
        public function listePrescription(PrescriptionRepository $PrescriptionRepository): Response
        {
            // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
            return $this->render('prescription/liste-prescription.html.twig', [
                'prescription' => $PrescriptionRepository->findAll()
            ]);
            
        }


    /**
     * @Route("/prescription", name="prescription")
     */
    public function newPrescription(Request $request,StructureRepository $StructureRepository,DossierRepository $DossierRepository,TypePrescriptionRepository $TypePrescriptionRepository): Response
    {
        $prescription = new Prescription();
        $resultat = "";
        $classe = "";
        $form = $this->createForm(PrescriptionType::class, $prescription);
         if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                $data = $request->request->get($form->getName());
                $dossiersId = $data['dossiers'];
                $prescriptiontypeId = $data['prescriptiontype'];
                $structuresId = $data['structures']; 
                $dossiers_entity = $DossierRepository->findOneBy(['id' => $dossiersId]);
                $prescriptiontype_entity = $TypePrescriptionRepository->findOneBy(['id' => $prescriptiontypeId]);
                $structures_entity = $StructureRepository->findOneBy(['id' => $structuresId]);
                $prescription->setDossiers($dossiers_entity);
                $prescription->setPrescriptiontype($prescriptiontype_entity);
                $prescription->setStructures($structures_entity);
                $this->entityManager->persist($prescription);
                $this->entityManager->flush();
             
             if ($prescription) {
                 return $this->redirectToRoute('listes_prescription');
             } else {
                 $resultat = "Echec de la creation!.";
                 $classe = "alert alert-danger";
                 return $this->render('prescription/prescription-form.html.twig', [
                    'action' => 'create',
                    'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
                 ]);
                 }
             }
             return $this->render('prescription/prescription-form.html.twig', [
                'controller_name' => 'PrescriptionController',
             'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
            ]);
            return $this->render('prescription/prescription-form.html.twig', [
                'controller_name' => 'PrescriptionController',
                'action' => 'create'
            ]);
        }



          /**
             * @Route("/{id}/editprescription", name="edit_prescription", methods={"GET","POST"})
             */
            public function editPrescription(Request $request, Prescription $prescription): Response
            {
                $form = $this->createForm(PrescriptionType::class, $prescription);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirectToRoute('listes_prescription');
                }
                $resultat = "";
                $classe = "";
                return $this->render('prescription/prescription-form.html.twig', [
                    'prescription' => $prescription,
                    'action' => 'edit',
                    'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe                ]);
            }
            

            /**
             * @Route("/{id}/supprimerprescription", name="delete_prescription", methods={"GET","POST"})
             */
            public function deletePrescription(Request $request,Prescription $prescription): Response
            {
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($prescription);
                $entityManager->flush();
                return $this->redirectToRoute('listestructure');
            }
}
