<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre del cliente',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('organisation', TextType::class, [
                'label' => 'Empresa del cliente (si posee alguna).',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Telefono del cliente',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo del cliente',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            /*
            ->add('rut', NumberType::class, [
                'label' => 'RUT del cliente',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])*/
            ->add('dv', ChoiceType::class, [
                'label' => 'Digito verificador',
                'placeholder' =>  'Seleccionar...',
                'choices' => $this->generateNumberChoices(),
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
            'data_class' => Customer::class,
        ]);
    }

    private function generateNumberChoices(): array
    {
        $choices = [];
        for ($i = 0; $i <= 9; $i++) {
            $choices[$i] = $i;
        }

        $choices['K'] = "K";

        return $choices;
    }
}
