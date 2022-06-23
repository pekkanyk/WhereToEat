<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastEmail = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            'last_username' => $lastEmail,
            'error'         => $error,
            'controller_name' => 'LoginController',
        ]);
    }

    /**
     * @Route("/logout", name="app_logout") 
     */
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        //throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
