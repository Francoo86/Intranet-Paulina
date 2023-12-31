<?php

namespace App\Form;

use App\Entity\Guideline;
use App\Entity\Manager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuidelineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //dd($options);
        $builder
            ->add('show_name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Nombre del programa'
                ])
            ->add('emission_number', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Número de emisión'
                ])
            ->add('creation_date', DateType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Fecha de emisión'
                ])
            ->add('broadcaster', ChoiceType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Locutor del programa'
                ])
            ->add('manager', EntityType::class, [
                'class' => Manager::class,
                'attr' => ['class' => 'form-control'],
                'label' => 'Manager del programa',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guideline::class,
        ]);
    }
}
