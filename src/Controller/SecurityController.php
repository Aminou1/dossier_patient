<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class SecurityController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserRepository $userRepository,EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, CsrfTokenManagerInterface $tokenManager = null)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenManager = $tokenManager;
        
    }

    //TODOO permettre apres de tenir compte du fos_user

    /**
     * @Route("login1/", name="login1")
     */
    public function login(AuthenticationUtils $authenticationUtils,UserRepository $userRepository,Request $request): Response
    {
        
        $userConnecte = $userRepository->findOneBy(['username' =>$request->get('username') ]);
       // return new Response($password);
        if ($userConnecte)  {
            $password = $this->passwordEncoder->isPasswordValid($userConnecte, $request->get('password'));
            if($password){
                return $this->redirectToRoute('accueil');
            }
            
        }
        /*if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('accueil');
          }*/
    
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
          
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    
}