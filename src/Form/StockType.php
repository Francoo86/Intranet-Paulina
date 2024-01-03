<?php

namespace App\Form;

use App\Entity\Publicity;
use App\Entity\Stock;
use App\Helper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('time', NumberType::class, [
                'label' => 'Seleccione la duración (en segundos)',
                'attr' => [
                    'class' => 'form-control',
                ],
                'html5' => true,
            ])
            ->add('amount', NumberType::class, [
                'label' => 'Seleccione el monto en pesos.',
                'attr' => [
                    'class' => 'form-control',
                ],
                'html5' => true,
            ])
            ->add('Publicity', EntityType::class, [
                'class' => Publicity::class,
                'label' => 'Seleccione publicidad para fijarle un stock',
                'placeholder' => 'Seleccionar publicidad...',
                'attr' => [
                    'class' => 'form-control',
                ],
                'query_builder' => Helper::GetNonSoftDeletedInForms(),
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
            'data_class' => Stock::class,
        ]);
    }
}
