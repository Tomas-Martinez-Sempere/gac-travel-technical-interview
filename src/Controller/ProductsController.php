<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Products;
use App\Entity\StockHistoric;
use App\Form\ProductType;
use App\Form\UpdateStockType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'app_products', methods: ["GET"])]
    public function index(ProductsRepository $productsRepository): Response
    {
        return $this->render('panel/products/list-products.html.twig', [
            'products' => $productsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_products_form', methods: ["GET","POST"])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() /*&& $form->isValid()*/) {

            $product = $form->getData();
            $fecha = new \DateTime();
            $product->setCreatedAt($fecha);
            $product->setStock(0);

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->render('panel/products/list-products.html.twig', [
                'products' => $entityManager->getRepository(Products::class)->findAll(),
            ]);
        }

        return $this->render('panel/products/form-products.html.twig', [
            'form' => $form->createView(),
            'categories' => $entityManager->getRepository(Categories::class)->findAll(),
            'accion' => 'add'
        ]);
    }

    #[Route('/update/{id}', name: 'app_products_update', methods: ["GET","POST"])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Products::class)->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            $product = $form->getData();
            $fecha = new \DateTime();
            $product->setCreatedAt($fecha);
            $product->setStock(0);

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->render('panel/products/list-products.html.twig', [
                'products' => $entityManager->getRepository(Products::class)->findAll(),
            ]);
        }

        return $this->render('panel/products/form-products.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'categories' => $entityManager->getRepository(Categories::class)->findAll(),
            'accion' => 'update'
        ]);
    }

    #[Route('/delete/{id}', name: 'app_products_delete', methods: ["GET","POST"])]
    public function delete(int $id, EntityManagerInterface $entityManager) : Response
    {
        $product = $entityManager->getRepository(Products::class)->find($id);

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->render('panel/products/list-products.html.twig', [
            'products' => $entityManager->getRepository(Products::class)->findAll(),
        ]);
    }

    #[Route('/updatestock/{id}', name: 'app_products_update_stock', methods: ["GET","POST"])]
    public function updatestock(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Products::class)->find($id);
        $stock_actual = (int)$product->getStock();
        $form = $this->createForm(UpdateStockType::class, $product);
        $form->handleRequest($request);
        $product_form = $form->getData();
        $variacion_stock = (int)$product_form->getStock();

        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            $stock_final = $stock_actual + $variacion_stock;
            if ($stock_final < 0)
            {
                $product->setStock($stock_actual);
                return $this->render('panel/products/form-stock-products.html.twig', [
                    'form' => $form->createView(),
                    'product' => $product,
                    'error' => "No puedo haber stock negativo"
                ]);
            }

            $product->setStock($stock_final);
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->render('panel/products/list-products.html.twig', [
                'products' => $entityManager->getRepository(Products::class)->findAll(),
            ]);
        }

        return $this->render('panel/products/form-stock-products.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'error' => ""
        ]);
    }

    #[Route('/liststockchanges/{id}', name: 'app_list_update_stock', methods: ["GET"])]
    public function listStockHistoric(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Products::class)->find($id);
        $stockhistoric = $entityManager->getRepository(StockHistoric::class)->findBy(['product' => $product]);
        return $this->render('panel/products/list-stockchanges.html.twig', [
            'product' => $product,
            'stockhistoric' => $stockhistoric,
        ]);
    }
}
