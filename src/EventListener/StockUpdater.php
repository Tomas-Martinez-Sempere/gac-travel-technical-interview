<?php

namespace App\EventListener;

use App\Entity\Products;
use App\Entity\StockHistoric;
use App\Entity\Users;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StockUpdater
{
    private $tokenStorage;

    private $em;

    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $entityManager;
    }

    public function postUpdate(Products $product, LifecycleEventArgs $event): void
    {
        if ($product->getValorAuxiliar() != 0)
        {
            $token = $this->tokenStorage->getToken();
            $user = $token->getUser();
            $fecha = new \DateTime();

            $stock = new StockHistoric();
            $stock->setProduct($product);
            $stock->setUser($user);
            $stock->setStock($product->getValorAuxiliar());
            $stock->setCreatedAt($fecha);

            $this->em->persist($stock);
            $this->em->flush();
        }
    }
}