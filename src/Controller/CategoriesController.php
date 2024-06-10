<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Products;
use App\Form\CategorieType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function add(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $categoria = new Categories();
        $form = $this->createForm(CategorieType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            $categoria_form = $form->getData();
            $fecha = new \DateTime();
            $categoria_form->setCreatedAt($fecha);

            $entityManager->persist($categoria_form);
            $entityManager->flush();

            return $this->redirectToRoute('app_categories');
        }

        return $this->renderForm('panel/categories/form-categories.html.twig', [
            'form' => $form,
            'categorie' => $categoria,
            'accion' => 'add'
        ]);
    }

    #[Route('/update/{id}', name: 'app_categories_update', methods: ["GET","POST"])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $categoria = $entityManager->getRepository(Categories::class)->find($id);
        $form = $this->createForm(CategorieType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            $categoria_form = $form->getData();
            $entityManager->persist($categoria_form);
            $entityManager->flush();

            return $this->redirectToRoute('app_categories');
        }

        return $this->renderForm('panel/categories/form-categories.html.twig', [
            'form' => $form,
            'categorie' => $categoria,
            'accion' => 'update'
        ]);
    }

    #[Route('/delete/{id}', name: 'app_categories_delete', methods: ["GET","POST"])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(Categories::class)->find($id);
        $product = $entityManager->getRepository(Products::class)->findBy(['category' => $category]);

        // Borramos en cascada los productos asociados a dicha categoria, y el historico asociado a dicho producto
        $query1 = $entityManager->createQuery(
            'DELETE FROM App\Entity\StockHistoric s WHERE s.product = :product'
        )->setParameter('product', $product);
        $query1->execute();

        $query2 = $entityManager->createQuery(
            'DELETE FROM App\Entity\Products p WHERE p.category = :category'
        )->setParameter('category', $category);
        $query2->execute();

        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('app_categories');
    }
}
