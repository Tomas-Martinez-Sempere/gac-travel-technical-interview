<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
class UsersController extends AbstractController
{
    #[Route('/', name: 'app_users')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('panel/users/list-users.html.twig', [
            'users' => $entityManager->getRepository(Users::class)->findAll(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_categories_delete', methods: ["GET","POST"])]
    public function delete(int $id, EntityManagerInterface $entityManager) : Response
    {
        $users = $entityManager->getRepository(Users::class)->find($id);

        $entityManager->remove($users);
        $entityManager->flush();

        return $this->render('panel/categories/list-categories.html.twig', [
            'users' => $entityManager->getRepository(Users::class)->findAll(),
        ]);
    }
}
