<?php

namespace App\Form;

use App\Entity\Broadcaster;
use App\Entity\Guideline;
use App\Entity\Manager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuidelineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('show_name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Nombre del programa'
                ])
            ->add('emission_number', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Número de emisión',
                'html5' => true
                ])
            ->add('creation_date', DateType::class, [
                'attr' => ['class' => 'form-control js-datepicker'],
                'label' => 'Fecha de emisión',
                'widget' => 'single_text',
                ])
            ->add('broadcaster', EntityType::class, [
                'class' => Broadcaster::class,
                'attr' => ['class' => 'form-control'],
                'label' => 'Locutor del programa',
                'empty_data' => null,
                'required' => false,
                'placeholder' => 'Seleccionar locutor',
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
            'data_class' => Guideline::class,
        ]);
    }
}
