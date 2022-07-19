<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\UserType;
use App\Form\EditUserType;
use App\Form\PatientType;
use App\Form\MedecinType;
use App\Form\ResetPasswordType;
use App\Form\EditPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\TypeUtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Constante\Constantes;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Validator\Constraints\UserPasswordValidator;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class UtilisateursController extends AbstractController
{
    private $TypeUtilisateurRepository;
    private $entityManager;
    private $passwordEncoder;
    private $tokenManager;
    private $formFactory;
    private $userManager;
    private $userRepository;
    private $userPasswordValidator;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository,
                                TypeUtilisateurRepository $TypeUtilisateurRepository, UserRepository $UserRepository,
                                UserManagerInterface $userManager, UserPasswordEncoderInterface $passwordEncoder,
                                CsrfTokenManagerInterface $tokenManager = null)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->TypeUtilisateurRepository = $TypeUtilisateurRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenManager = $tokenManager;
        $this->userManager = $userManager;
    }



    /**
     * @Route("/listesutilisateur", name="listesutilisateur")
     */
    public function listeUser(): Response
    {

        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $users = $this->getDoctrine()->getRepository(User::class)->findBy([]);
        return $this->render('utilisateurs/utilisateur-listes.html.twig', [
            'users' => $users
        ]);
        
    }

    /**
     * @Route("/listesmedecin", name="listesmedecin")
     */
    public function listeMedecin(): Response
    {

        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $users = $this->getDoctrine()->getRepository(User::class)->findBy([]);
        return $this->render('utilisateurs/medecin-liste.html.twig', [
            'users' => $users
        ]);
        
    }

     /**
     * @Route("/listespatient", name="listespatient")
     */
    public function listePatient(): Response
    {

        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $users = $this->getDoctrine()->getRepository(User::class)->findBy([]);
        return $this->render('utilisateurs/patient-listes.html.twig', [
            'users' => $users
        ]);
        
    }


    /**
     * @Route("/users_index", name="users_index")
     */
    public function listeUsers(UserRepository $UserRepository): Response
    {
        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $users = $this->getDoctrine()->getRepository(User::class)->findBy([]);
        $resultat =  "";
        $classe = "";
        return $this->render('utilisateurs/utilisateur-listes.html.twig', [
            'users' => $users,
            'resultat' => $resultat,'classe' => $classe
        ]);
     }


      /**
     * @Route("/patient_index", name="patient_index")
     */
    public function listePatients(UserRepository $UserRepository): Response
    {
        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $users = $this->getDoctrine()->getRepository(User::class)->findBy([]);
        $resultat =  "";
        $classe = "";
        return $this->render('utilisateurs/patient-listes.html.twig', [
            'users' => $users,
            'resultat' => $resultat,'classe' => $classe
        ]);
     }


     /**
     * @Route("/newuser", name="newuser")
     * 
     */
    public function addUsers(Request $request, TypeUtilisateurRepository $TypeUtilisateurRepository): Response
    {
       $user = new User();
       $users = $this->getUser();
       $resultat = "";
       $classe = "";
       $form = $this->createForm(UserType::class, $user);
       $roles = $users->getRoles();
       $role = $roles[0];
       if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
           $data = $request->request->get($form->getName());
           $user->setPassword($this->passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));
           $email= $data['telephone'].'@gmail.com';
           $user->setEmail($email);
           if($data['utilisateur']==1){
               $roles = ['ROLE_USER', 'ROLE_PATIENT'];
               $user->setRoles($roles);
               $user->setEnabled(true);
           }elseif($data['utilisateur']==2){
               $roles = ['ROLE_USER', 'ROLE_MEDECIN'];
               $user->setRoles($roles);
               $user->setEnabled(true);
           }elseif($data['utilisateur']==3){
               $roles = ['ROLE_USER', 'ROLE_SECRETAIRE'];
               $user->setRoles($roles);
               $user->setEnabled(true);
           }elseif($data['utilisateur']==4){
               $roles = ['ROLE_USER', 'ROLE_ADMIN'];
               $user->setRoles($roles);
               $user->setEnabled(true);
           } else{
               ///
           }

           $utilisateurTypeId = $data['utilisateur'];
           $utilisateurType_entity = $TypeUtilisateurRepository->findOneBy(['id' => $utilisateurTypeId]);
           $user->setUtilisateur($utilisateurType_entity);
           $this->entityManager->persist($user);
           $this->entityManager->flush();
           
           if ($user) {
               return $this->redirectToRoute('users_index');
           } else {
               $resultat = "Echec de la creation!.";
               $classe = "alert alert-danger";
               return $this->render('utilisateurs/utilisateur.html.twig', [
                   'action' => 'createagent',
                   'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
               ]);
           }
       }
       return $this->render('utilisateurs/utilisateur.html.twig', [
       'controller_name' => 'UtilisateursController',
       'action' => 'createagent',
       'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
       ]);
    }


    /**
     * @Route("/newpatient", name="new_patient")
     * 
     */
     public function addPatient(Request $request, TypeUtilisateurRepository $TypeUtilisateurRepository): Response
     {
        $user = new User();
        $users = $this->getUser();
        $resultat = "";
        $classe = "";
        $form = $this->createForm(PatientType::class, $user);
        $roles = $users->getRoles();
        $role = $roles[0];
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $data = $request->request->get($form->getName());
            $user->setPassword($this->passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));
            $email= $data['telephone'].'@gmail.com';
            $user->setEmail($email);

          if($data['utilisateur']==1){
                $roles = ['ROLE_USER', 'ROLE_PATIENT'];
                $user->setRoles($roles);
                $user->setEnabled(true);
            }else{
                ///
            }

            $utilisateurTypeId = $data['utilisateur'];
            $utilisateurType_entity = $TypeUtilisateurRepository->findOneBy(['id' => $utilisateurTypeId]);
            $user->setUtilisateur($utilisateurType_entity);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            
            if ($user) {
                return $this->redirectToRoute('patient_index');
            } else {
                $resultat = "Echec de la creation!.";
                $classe = "alert alert-danger";
                return $this->render('utilisateurs/utilisateur.html.twig', [
                    'action' => 'createpatient',
                    'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
                ]);
            }
        }
        return $this->render('utilisateurs/utilisateur.html.twig', [
        'controller_name' => 'UtilisateursController',
        'action' => 'createpatient',
        'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
        ]);
     }

      /**
     * @Route("/newmedecin", name="new_medecin")
     * 
     */
    public function addMedecin(Request $request, TypeUtilisateurRepository $TypeUtilisateurRepository): Response
    {
       $user = new User();
       $users = $this->getUser();
       $resultat = "";
       $classe = "";
       $form = $this->createForm(MedecinType::class, $user);
       $roles = $users->getRoles();
       $role = $roles[0];
       if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
           $data = $request->request->get($form->getName());
           $user->setPassword($this->passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));
         if($data['utilisateur']==2){
               $roles = ['ROLE_USER', 'ROLE_MEDECIN'];
               $user->setRoles($roles);
               $user->setEnabled(true);
           }else{
               ///
           }

           $utilisateurTypeId = $data['utilisateur'];
           $utilisateurType_entity = $TypeUtilisateurRepository->findOneBy(['id' => $utilisateurTypeId]);
           $user->setUtilisateur($utilisateurType_entity);
           $this->entityManager->persist($user);
           $this->entityManager->flush();
           
           if ($user) {
               return $this->redirectToRoute('listesmedecin');
           } else {
               $resultat = "Echec de la creation!.";
               $classe = "alert alert-danger";
               return $this->render('utilisateurs/utilisateur.html.twig', [
                   'action' => 'createmedecin',
                   'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
               ]);
           }
       }
       return $this->render('utilisateurs/utilisateur.html.twig', [
       'controller_name' => 'UtilisateursController',
       'action' => 'createmedecin',
       'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
       ]);
    }


        /**
         * @Route("/{id}/usersedit", name="user_edit", methods={"GET","POST"})
         * 
         */
        public function editUser(Request $request, User $User): Response
        {
            $form = $this->createForm(EditUserType::class, $User);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('users_index');
            }
            $resultat = "";
            $classe = "";
            return $this->render('utilisateurs/utilisateur-form-edit.html.twig', [
                'users' => $User,
                'action' => 'editagent',
                'resultat' => $resultat,'classe' => $classe,
                'form' => $form->createView(),
            ]);
        }


          /**
         * @Route("/{id}/userspatient", name="user_patient", methods={"GET","POST"})
         * 
         */
        public function editPatient(Request $request, User $User): Response
        {
            $form = $this->createForm(EditUserType::class, $User);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('users_index');
            }
            $resultat = "";
            $classe = "";
            return $this->render('utilisateurs/utilisateur-form-edit.html.twig', [
                'users' => $User,
                'action' => 'editpatient',
                'resultat' => $resultat,'classe' => $classe,
                'form' => $form->createView(),
            ]);
        }

          /**
         * @Route("/{id}/usermedecin", name="user_medecin", methods={"GET","POST"})
         * 
         */
        public function editMedecin(Request $request, User $User): Response
        {
            $form = $this->createForm(EditUserType::class, $User);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('users_index');
            }
            $resultat = "";
            $classe = "";
            return $this->render('utilisateurs/utilisateur-form-edit.html.twig', [
                'users' => $User,
                'action' => 'editmedecin',
                'resultat' => $resultat,'classe' => $classe,
                'form' => $form->createView(),
            ]);
        }

         //TODOO permettre apres de tenir compte du fos_user
        /**
         * @Route("/logout", name="logout")
         */
        public function logoutAction(Request $request) {
            //throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
            $request->getSession()->invalidate();
            //return $this->redirectToRoute('login');
        }

        /**
        * @Route("/login", name="login")
        */
        public function login(Request $request)
        {
            $session = $request->getSession();

            $authErrorKey = Security::AUTHENTICATION_ERROR;
            $lastUsernameKey = Security::LAST_USERNAME;

            // get the error if any (works with forward and redirect -- see below)
            if ($request->attributes->has($authErrorKey)) {
                $error = $request->attributes->get($authErrorKey);
            } elseif (null !== $session && $session->has($authErrorKey)) {
                $error = $session->get($authErrorKey);
                $session->remove($authErrorKey);
            } else {
                $error = null;
            }

            if (!$error instanceof AuthenticationException) {
                $error = null; // The value does not come from the security component.
            }

            // last username entered by the user
            $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);
            $message = "Les informations d'indentification sont invalides";
            $csrfToken = $this->tokenManager
                ? $this->tokenManager->getToken('authenticate')->getValue()
                : null;

            return $this->render('login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
                'csrf_token' => $csrfToken,
                'message' => $message
            ]);
        }



    /**
     * @Route("/activer/{id}", name="enabled_user")
     * 
     */
    public function activerUser(Request $request, User $user_entity): Response
    {
        $user = $this->getUser();
        $roles = $user->getRoles();
        $role = $roles[0];    
        $entityManager = $this->getDoctrine()->getManager();
            $user_entity->setEnabled(1);
            $entityManager->persist($user);
            $entityManager->flush();
            $users = $this->userRepository->getUsers();
            if ($entityManager) {
                return $this->redirectToRoute('users_index');
            }else {
                return $this->render('utilisateurs/utilisateur-listes.html.twig', [
                    'users' => $users,
                ]);
            }
        
            return $this->render('utilisateurs/utilisateur-listes.html.twig', [
                'users' => $users
            ]);
    }


     /**
     * @Route("/desactiver/{id}", name="desabled_user")
     *
     */
    public function desactiverUser(Request $request, User $user_entity): Response
    {
        $user = $this->getUser();
        $roles = $user->getRoles();
        $role = $roles[0];    
        $entityManager = $this->getDoctrine()->getManager();
        $resultat = "";
        $classe = "";
        $form = $this->createForm(UserType::class, $user);
            $user_entity->setEnabled(0);
            $entityManager->persist($user);
            $entityManager->flush();
            $users = $this->userRepository->getUsers();            
            if ($entityManager) {
                return $this->redirectToRoute('users_index');
            }else {
                return $this->render('utilisateurs/utilisateur-listes.html.twig', [
                    'users' => $users,
                ]);
            }
        
            return $this->render('utilisateurs/utilisateur-listes.html.twig', [
                'users' => $users
            ]);
    }
  
    
      /**
     * @Route("/user/resetpassword", name="reset_password")
     */
    public function resetPassword(Request $request,UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $roles = $user->getRoles();
        $role = $roles[0];
       
        if(!$user){
           //     
        }
        $form = $this->createForm(ResetPasswordType::class, null);
        $resultat = "";
        $classe = "";
        if($role !== Constantes::ROLE_ADMIN){
            $resultat = "Vous n'avez pas l'autorisation sur cette page.";
            $classe = "alert alert-danger";
            return $this->render('utilisateurs/password-form-reset.html.twig', [
                'action' => 'edit',
                'users' => $user,
                'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
            ]);
        }
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $data = $request->request->get($form->getName());
            $username = $data['username'];
            $user_entity = $this->userManager->findUserByUsernameOrEmail($username);
            //return new Response($user_entity->getUsername());
            if(!$user_entity){
                $resultat = "Cet utilisateur n'a pas de compte.";
                $classe = "alert alert-danger";
            
                return $this->render('utilisateurs/password-form-reset.html.twig', [
                    'action' => 'edit',
                    'users' => $user,
                    'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
                ]);
            }
                $motdepassarray = $data['plainPassword'];
                $motdepass = $motdepassarray['first'];    
                $user_entity->setPlainPassword($motdepass);
                //$userManager = $this->get('fos_user.resetting.form.factory');
               // $userManager->updateUser($user_entity);
                $this->userManager->updateUser($user_entity);
               // $user->setPassword($this->passwordEncoder->encodePassword($user, $request->get('plainPassword')));
               // $this->userManager->flush();
            
            
            if ($this->userManager) {
                $resultat = "Réinitialisation réussi, voici le mot de passe à communiquer: $motdepass .";
                $classe = "alert alert-info";
            
                return $this->render('utilisateurs/password-form-reset.html.twig', [
                    'action' => 'edit',
                    'users' => $user,
                    'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
                ]);
            } else {
                $resultat = "Echec de la réinitialisation.";
                $classe = "alert alert-danger";
            
                return $this->render('utilisateurs/password-form-reset.html.twig', [
                    'action' => 'edit',
                    'users' => $user,
                    'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
                ]);
            }
        }
        return $this->render('utilisateurs/password-form-reset.html.twig', [
            'action' => 'edit',
            'users' => $user,
            'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
        ]);
    }


       /**
     * @Route("/user/editpassword", name="edit_password")
     */
    public function editPassword(Request $request,UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        if(!$user){
            //     
        }
        $form = $this->createForm(EditPasswordType::class, null);
        $resultat = "";
        $classe = "";
            
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $data = $request->request->get($form->getName());
            $user_entity = $userRepository->findOneBy(['id'=>$user->getId()]);
            $password = $data['ancienpassword'];
            
            $motdepasse=  $this->passwordEncoder->isPasswordValid($user_entity, $password);
            
            if($motdepasse){
                $motdepassarray = $data['plainPassword'];
                $motdepass = $motdepassarray['first'];    
                $user_entity->setPlainPassword($motdepass);
               $this->userManager->updateUser($user_entity);
              
            }
            
            if ($user) {
                return $this->redirectToRoute('list_user');
            } else {
                $resultat = "Echec de la modification.";
                $classe = "alert alert-danger";
            
                return $this->render('utilisateurs/password-form-edit.html.twig', [
                    'action' => 'edit',
                    'user' => $user,
                    'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
                ]);
            }
        }
        return $this->render('utilisateurs/password-form-edit.html.twig', [
            'action' => 'edit',
            'user' => $user,
            'form' => $form->createView(),'resultat' => $resultat,'classe' => $classe
        ]);
    }
}

   
