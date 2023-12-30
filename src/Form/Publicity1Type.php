<?php

namespace App\Form;

use App\Form\GuidelineType;
use App\Entity\Publicity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Publicity1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('guideline', GuidelineType::class, ["data_class" => null])
            ->add('sentence')
            ->add('background')
            ->add('duration')
            ->add('description')
            ->add('Audience')
            //->add('Stock')
            ->add('customer')
            ->add('stock')
            ->add('Guideline')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publicity::class,
        ]);
    }
}
