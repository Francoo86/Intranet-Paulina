<?php

namespace App\Form;

use App\Entity\Audience;
use App\Entity\Customer;
use App\Entity\Guideline;
use App\Entity\Publicity;
use App\Entity\Show;
use App\Entity\Stock;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sentence', TextareaType::class, [
                'label' => 'Frase de la publicidad/propaganda',
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('background', TextType::class, [
                'label' => 'Fondo músical',
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Duración de la publicidad (en segundos).',
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción de la publicidad',
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            //->add('Stock')
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'label' => "Cliente",
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('Show', EntityType::class, [
                'class' => Show::class,
                'label' => "Programa",
                'attr' => [
                    'class' => 'form-control'
                ],
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('g')
                        ->where('g.DeletedAt is NULL');
                }
            ])
            ->add('Audience', EntityType::class, [
                'class' => Audience::class,
                'label' => "Público objetivo",
                'attr' => [
                    'class' => 'form-control'
                ],
                'choice_label' => function(Audience $audience) {
                    return sprintf('%s-%s : %s', $audience->getDemography(), $audience->getLocality(), $audience->getType());
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                              ->where('a.DeletedAt IS NULL');
                },
            ])
            ->add('Guideline', EntityType::class, [
                'class' => Guideline::class,
                'label' => "Pauta",
                'placeholder' => 'Seleccione pauta',
                'attr' => [
                    'class' => 'form-control'
                ],
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('g')
                        ->where('g.DeletedAt is NULL');
                },
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
            'data_class' => Publicity::class,
        ]);
    }
}
