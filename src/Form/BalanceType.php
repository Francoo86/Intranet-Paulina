<?php

namespace App\Form;

use App\Entity\Balance;
use App\Entity\Stock;
use Shapecode\Bundle\HiddenEntityTypeBundle\Form\Type\HiddenEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BalanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('amount', NumberType::class, [
                'label' => 'Monto de la bolsa',
                'attr' => [
                    'class' => 'form-control',
                ],
                'html5' => true,
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Estado activo',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('stock', HiddenEntityType::class, [
                'label' => false,
                'class' => Stock::class,
                'data' =>  $options['stock']
            ])
            ->add('saveEdit', SubmitType::class, [
                'label' => "Guardar cambios",
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Balance::class,
            'stock' => null,
        ]);
    }
}
