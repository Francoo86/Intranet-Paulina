<?php

namespace App\Form;

use App\Entity\Guideline;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuidelineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('show_name')
            ->add('emission_number')
            ->add('creation_date')
            ->add('broadcaster')
            ->add('manager')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guideline::class,
        ]);
    }
}
