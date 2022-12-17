<?php

namespace App\Form\AdminPanel\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'error_bubbling' => true,
                'label' => false,
                'attr' => [
                    'maxlength' => false,
                    'placeholder' => 'Correo electrónico',
                ]
            ])
            ->add('password', PasswordType::class, [
                'required' => $options['edit'] ? false : true,
                'mapped' => $options['edit'] ? false : true,
                'error_bubbling' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Contraseña',
                ]
            ]);

        if (! $options['edit']) {
            $builder
            ->add('repeatPassword', PasswordType::class, [
                'required' => $options['edit'] ? false : true,
                'error_bubbling' => true,
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Repetir contraseña',
                ]
            ]);
        }

        $builder
            ->add('name', TextType::class, [
                'error_bubbling' => true,
                'label' => false,
                'attr' => [
                    'maxlength' => false,
                    'placeholder' => 'Nombre',
                ]
            ])
            ->add('firstname', TextType::class, [
                'error_bubbling' => true,
                'label' => false,
                'attr' => [
                    'maxlength' => false,
                    'placeholder' => 'Primer apellido',
                ]
            ])
            ->add('lastname', TextType::class, [
                'required' => false,
                'error_bubbling' => true,
                'label' => false,
                'attr' => [
                    'maxlength' => false,
                    'placeholder' => 'Segundo apellido',
                ]
            ]);

            if ($options['edit']) {
                $builder
                ->add('roles', ChoiceType::class, [
                    'mapped' => false,
                    'error_bubbling' => true,
                    'label' => 'Role',
                    'choices' => [
                        'Usuario' => '["ROLE_USER"]',
                        'Administrador' => '["ROLE_ADMIN"]',
                    ],
                ]);
            }

        $builder
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'label' => 'Imagen',
                'allow_delete' => false,
                'download_uri' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'edit' => false,
        ]);
    }
}
