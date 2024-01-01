<?php

namespace App\Form;

use App\Entity\Guideline;
use App\Entity\Show;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType as TypeTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Nombre del programa'
            ])
            ->add('start', TypeTimeType::class, [
                'html5' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'widget' => 'single_text',
                'label' => 'Hora de comienzo'
            ])
            ->add('finish', TypeTimeType::class, [
                'html5' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'widget' => 'single_text',
                'label' => 'Hora de cierre'
            ])
            ->add('Guideline', EntityType::class, [
                'placeholder' => 'Seleccionar pauta',
                'class' => Guideline::class,
                'autocomplete' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Pauta asociada al programa'
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
            'data_class' => Show::class,
        ]);
    }
}
