<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/', name: 'app_login', methods: ['GET'])]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('login/index.html.twig', [
            'last_username' => '',
            'error' => ''
        ]);
    }

    #[Route('/', name: 'app_checklogin', methods: ['POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        //$lastUsername = $authenticationUtils->getLastUsername();

        if ($error) {
            return $this->render('error.html.twig');
        }

        return $this->render('panel/users/list-users.html.twig');
    }


    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        //return $this->render('login/index.html.twig');

        // Este método puede permanecer vacío - Symfony se encargará del proceso de logout
        throw new \Exception('This should never be reached!');
    }

}
