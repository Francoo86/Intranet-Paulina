<?php

namespace App\Form;

use App\Entity\Publicity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sentence')
            ->add('background')
            ->add('duration')
            ->add('description')
            ->add('Audience')
            ->add('audience')
            ->add('Stock')
            ->add('stock')
            ->add('Customer')
            ->add('customer')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publicity::class,
        ]);
    }
}