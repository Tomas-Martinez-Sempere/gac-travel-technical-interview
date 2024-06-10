<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Products;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateStockType extends AbstractType
{
    public function buildForm($builder, array $options): void
    {
        $builder
            //->add('name')
            //->add('created_at')
            //->add('category')
            ->add('Stock', NumberType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}