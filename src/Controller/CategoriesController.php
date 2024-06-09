<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategorieType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories')]
class CategoriesController extends AbstractController
{
    #[Route('/', name: 'app_categories', methods: ["GET"])]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('panel/categories/list-categories.html.twig', [
            'categories' => $categoriesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categories_form', methods: ["GET","POST"])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoria = new Categories();
        $form = $this->createForm(CategorieType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            $categoria = $form->getData();
            $fecha = new \DateTime();
            $categoria->setCreatedAt($fecha);

            $entityManager->persist($categoria);
            $entityManager->flush();

            return $this->render('panel/categories/list-categories.html.twig', [
                'categories' => $entityManager->getRepository(Categories::class)->findAll()
            ]);
        }

        return $this->renderForm('panel/categories/form-categories.html.twig', [
            'form' => $form,
            'categorie' => $categoria,
            'accion' => 'add'
        ]);
    }

    #[Route('/update/{id}', name: 'app_categories_update', methods: ["GET","POST"])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoria = $entityManager->getRepository(Categories::class)->find($id);
        $form = $this->createForm(CategorieType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            $categoria_form = $form->getData();
            $categoria->setName($categoria_form->getName());

            $entityManager->persist($categoria);
            $entityManager->flush();

            return $this->render('panel/categories/list-categories.html.twig', [
                'categories' => $entityManager->getRepository(Categories::class)->findAll()
            ]);
        }

        return $this->renderForm('panel/categories/form-categories.html.twig', [
            'form' => $form,
            'categorie' => $categoria,
            'accion' => 'update'
        ]);
    }

    #[Route('/delete/{id}', name: 'app_categories_delete', methods: ["GET","POST"])]
    public function delete(int $id, EntityManagerInterface $entityManager) : Response
    {
        $category = $entityManager->getRepository(Categories::class)->find($id);

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->render('panel/categories/list-categories.html.twig', [
            'categories' => $entityManager->getRepository(Categories::class)->findAll(),
        ]);
    }
}
