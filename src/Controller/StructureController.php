<?php

namespace App\Controller;
use App\Form\StructureFormType;
use App\Entity\Structure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Constante\Constantes;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Validator\Constraints\UserPasswordValidator;
use App\Repository\StructureRepository;

class StructureController extends AbstractController
{


    private $StructureRepository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager,StructureRepository $StructureRepository)
    {
        $this->entityManager = $entityManager;
        $this->StructureRepository = $StructureRepository;
    }


    /**
     * @Route("/structure", name="structure")
     */

   public function newStructure(Request $request): Response
    {
       $structure = new Structure();
       $resultat = "";
       $classe = "";
       $form = $this->createForm(StructureFormType::class, $structure);
       
       if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
           $data = $request->request->get($form->getName());
           $this->entityManager->persist($structure);
           $this->entityManager->flush();
           
           if ($structure) {
               return $this->redirectToRoute('listestructure');
           } else {
               $resultat = "Echec de la creation!.";
               $classe = "alert alert-danger";
               return $this->render('structure/structure-form.html.twig', [
                   'action' => 'create',
                   'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
               ]);
            }
        }
        return $this->render('structure/structure-form.html.twig', [
        'controller_name' => 'StructureController',
        'action' => 'create',
        'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
        ]);
        }



       /**
         * @Route("/listestructure", name="listestructure")
         */
            public function listeStructure(): Response
            {

                // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                $structures = $this->getDoctrine()->getRepository(Structure::class)->findBy([]);
                return $this->render('structure/liste-structure.html.twig', [
                    'structures' => $structures
                ]);
                
            }

        /**
             * @Route("/{id}/supprimerstructure", name="delete_structure", methods={"GET","POST"})
             */
            public function deleteStructure(Request $request, Structure $structure): Response
            {
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($structure);
                $entityManager->flush();
                return $this->redirectToRoute('listestructure');
            }

            /**
             * @Route("/{id}/editstructure", name="edit_structure", methods={"GET","POST"})
             */
            public function editStructure(Request $request, Structure $structure): Response
            {
                $form = $this->createForm(StructureFormType::class, $structure);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirectToRoute('listestructure');
                }
                $resultat = "";
                $classe = "";
                return $this->render('structure/structure-form.html.twig', [
                    'structure' => $structure,
                    'action' => 'edit',
                    'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe                ]);
            }
}
