<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class MenuController extends AbstractController
{

    private $tokenManager;

    public function __construct(CsrfTokenManagerInterface $tokenManager = null)
    {
        $this->tokenManager = $tokenManager;
    }


    /**
     * @Route("/", name="menu")
     */
    public function Accueil(): Response
    {

        $user = $this->getUser();
        $roles = $user->getRoles();
        $role = $roles[0];
        return $this->render('menu/menu.html.twig', [
            'controller_name' => 'MenuController',
        ]);
    }

}
