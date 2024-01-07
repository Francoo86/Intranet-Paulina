<?php

namespace App\Form;

use App\Entity\Audience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AudienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /*$builder
            ->add('demography')
            ->add('locality')
            ->add('type')
        ;*/
        $builder
            ->add('demography', ChoiceType::class, [
                'choices' => [
                    'Niñez' => 'niñez',
                    'Adolescencia' => 'adolescencia',
                    'Adulto joven' => 'adulto joven',
                    'Adulto' => 'adulto',
                    'Adulto mayor' => 'adulto mayor',
                    'General' => 'general',
                    'Sin definir' => 'Sin definir',
                ],
                'label' => 'Demografía',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('locality', ChoiceType::class, [
                'choices' => [
                    'Local' => 'local',
                    'Regional' => 'regional',
                    'Nacional' => 'nacional',
                ],
                'label' => 'Localidad',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('type', TextType::class, [
                'label' => 'Categoria',
                'attr' => [
                    'class' => 'form-control'
                ]
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
            'data_class' => Audience::class,
        ]);
    }
}
