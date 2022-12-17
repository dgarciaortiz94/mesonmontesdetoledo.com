<?php

namespace App\Form\AdminPanel\Profile;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SecurityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastPassword', PasswordType::class, [
                'required' => false,
                'error_bubbling' => true,
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Contraseña anterior',
                ]
            ])
            ->add('password', PasswordType::class, [
                'required' => false,
                'error_bubbling' => true,
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Contraseña nueva',
                ]
            ])
            ->add('repeatPassword', PasswordType::class, [
                'required' => false,
                'error_bubbling' => true,
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Repetir contraseña nueva',
                ]
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
