<?php

namespace App\Form\AdminPanel\Profile;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'error_bubbling' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email',
                ]
            ])
            ->add('name', TextType::class, [
                'error_bubbling' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nombre',
                ]
            ])
            ->add('firstname', TextType::class, [
                'error_bubbling' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Primer apellido',
                ]
            ])
            ->add('lastname', TextType::class, [
                'required' => false,
                'error_bubbling' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Segundo apellido',
                ]
            ])
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'error_bubbling' => true,
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
        ]);
    }
}
