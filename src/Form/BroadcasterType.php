<?php

namespace App\Form;

use App\Entity\Broadcaster;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BroadcasterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rut', NumberType::class, [
                'label' => 'RUT de la persona (sin puntos ni digito verificador).',
                'attr' => ['class' => 'form-control'],
                'html5' => true,
            ])
            /*
            ->add('dv', NumberType::class, [
                'label' => 'Digito verificador.',
                'attr' => ['class' => 'form-control'],
                'html5' => true,
            ])*/
            ->add('first_name', TextType::class, [
                'label' => 'Nombre',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Apellido',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo electronico',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Telefono del locutor',
                'attr' => ['class' => 'form-control'],
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
            'data_class' => Broadcaster::class,
        ]);
    }
}
