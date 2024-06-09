<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistroType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistroController extends AbstractController
{
    #[Route('/registro', name: 'app_registro', methods: ['POST','GET'])]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistroType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() /*&& $form->isValid()*/) {

            $user = $form->getData();
            $fecha = new DateTime();
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            //$user->setUsername($username);
            $user->setPassword($hashedPassword);
            $user->setActive(true);
            $user->setCreatedAt($fecha);
            $user->setRoles(['ROLE_ADMIN']);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render('registro/exito.html.twig');
        }

        return $this->render('registro/form-registro.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}