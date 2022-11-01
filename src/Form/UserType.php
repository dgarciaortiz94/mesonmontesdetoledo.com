<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
                'attr' => [
                    'maxlength' => false,
                    'placeholder' => 'Email',
                ]
            ])
            ->add('password', PasswordType::class, [
                'error_bubbling' => true,
                'attr' => [
                    'maxlength' => false,
                    'placeholder' => 'password',
                ]
            ])
            ->add('name', TextType::class, [
                'error_bubbling' => true,
                'attr' => [
                    'maxlength' => false,
                    'placeholder' => 'name',
                ]
            ])
            ->add('firstname', TextType::class, [
                'error_bubbling' => true,
                'attr' => [
                    'maxlength' => false,
                    'placeholder' => 'firstname',
                ]
            ])
            ->add('lastname', TextType::class, [
                'error_bubbling' => true,
                'attr' => [
                    'maxlength' => false,
                    'placeholder' => 'lastname',
                ]
            ])
            ->add('image', FileType::class, [
                'error_bubbling' => true,
                'required' => false,
                'label' => 'Image',
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
