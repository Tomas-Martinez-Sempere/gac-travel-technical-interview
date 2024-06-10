<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Products;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {
        // Añadimos un usuario
        $user = new Users();
        $fecha = new \DateTime();
        $user->setUsername("Usuario1");
        $user->setActive(true);
        $user->setCreatedAt($fecha);
        $user->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'Usuario1');
        $user->setPassword($hashedPassword);
        $manager->persist($user);
        $manager->flush();

        // Añadimos dos categorias
        $categoria1 = new Categories();
        $categoria1->setName("categoria1");
        $categoria1->setCreatedAt($fecha);
        $categoria2 = new Categories();
        $categoria2->setCreatedAt($fecha);
        $categoria2->setName("categoria2");
        $manager->persist($categoria1);
        $manager->persist($categoria2);
        $manager->flush();

        // Añadimos dos productos
        $producto1 = new Products();
        $producto1->setName("producto1");
        $producto1->setCreatedAt($fecha);
        $producto1->setCategory($categoria1);
        $producto1->setStock(100);
        $producto2 = new Products();
        $producto2->setName("producto2");
        $producto2->setCreatedAt($fecha);
        $producto2->setCategory($categoria2);
        $producto2->setStock(150);
        $manager->persist($producto1);
        $manager->persist($producto2);
        $manager->flush();

    }
}
