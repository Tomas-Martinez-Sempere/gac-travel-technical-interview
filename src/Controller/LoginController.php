<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/', name: 'app_login', methods: ['GET'])]
    public function index(AuthenticationUtils $authenticationUtils, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        if ($user) {
            return $this->redirectToRoute('app_users');
        }

        return $this->render('login/index.html.twig', [
            'last_username' => '',
            'error' => ''
        ]);
    }

    #[Route('/', name: 'app_checklogin', methods: ['POST'])]
    public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error) {
            return $this->render('error.html.twig');
        }

        return $this->redirectToRoute('app_users');
    }


    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {

        // Este método puede permanecer vacío - Symfony se encargará del proceso de logout
        throw new \Exception('This should never be reached!');
    }

}
