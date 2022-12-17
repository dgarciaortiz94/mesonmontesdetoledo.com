<?php

namespace App\Form\AdminPanel\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('GET')
            ->add('email', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email',
                ],
            ])
            ->add('name', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nombre',
                ],
            ])
            ->add('firstname', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Primer apellido',
                ],
            ])
            ->add('lastname', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Segundo apellido',
                ],
            ])
            // ->add('roles')
            ->add('registeredFrom', DateType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Registrado desde',
                'attr' => [
                    'class' => 'input-date',
                ]
            ])
            ->add('registeredTo', DateType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Registrado hasta',
                'attr' => [
                    'class' => 'input-date',
                ]
            ])
            ->add('isActive', ChoiceType::class, [
                'required' => false,
                'label' => 'Estado',
                'choices' => [
                    'Activo' => true,
                    'Bloqueado' => false,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
