<?php

namespace App\Controller;
use App\Form\DossierType;
use App\Entity\Dossier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Constante\Constantes;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Validator\Constraints\UserPasswordValidator;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use App\Repository\DossierRepository;
use Doctrine\ORM\EntityManagerInterface;

class DossierController extends AbstractController
{

    private $DossiereRepository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager,DossierRepository $DossierRepository)
    {
        $this->entityManager = $entityManager;
        $this->DosiereRepository = $DossierRepository;
    }


    /**
     * @Route("/dossierform", name="dossier_form")
     */

    public function CreerDossier(Request $request): Response
    {
       $dossier = new Dossier();
       $resultat = "";
       $classe = "";
       $datej = date('Y-m-d H:i:s');
       $date = new \DateTime($datej);
       $form = $this->createForm(DossierType::class, $dossier);
       if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
           $data = $request->request->get($form->getName());

           $utilisateurTypeId = $data['utilisateur'];
           $utilisateur_entity = $UserRepository->findOneBy(['id' => $utilisateurTypeId]);
           $dossier->setUtilisateur($utilisateur_entity);

           $dossier->setDateCreation($date);
           $this->entityManager->persist($dossier);
           $this->entityManager->flush();
           
            if ($dossier) {
                return $this->redirectToRoute('dossier');
            } else {
                $resultat = "Echec de la creation!.";
                $classe = "alert alert-danger";
                return $this->render('dossier/dossier-form.html.twig', [
                    'action' => 'create',
                    'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
                ]);
                }
            }
            return $this->render('dossier/dossier-form.html.twig', [
            'controller_name' => 'DossierController',
            'action' => 'create',
            'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
            ]);
        }

         /**
         * @Route("/dossier", name="dossier")
         */
        public function listeDossier(): Response
        {

            // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
            $dossier = $this->getDoctrine()->getRepository(Dossier::class)->findBy([]);
            return $this->render('dossier/liste-dossier.html.twig', [
                'dossierPatient' => $dossier
            ]);
            
        }


          /**
             * @Route("/{id}/supprimerdossier", name="delete_dossier", methods={"GET","POST"})
             */
            public function deleteStructure(Request $request, Dossier $dossier): Response
            {
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($dossier);
                $entityManager->flush();
                return $this->redirectToRoute('dossier');
            }

            /**
             * @Route("/{id}/editdossier", name="edit_dossier", methods={"GET","POST"})
             */
            public function editStructure(Request $request, Dossier $dossier): Response
            {
                $form = $this->createForm(DossierType::class, $dossier);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirectToRoute('dossier');
                }
                $resultat = "";
                $classe = "";
                return $this->render('dossier/dossier-form.html.twig', [
                    'dossier' => $dossier,
                    'action' => 'edit',
                    'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe                ]);
            }

}
