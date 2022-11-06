<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin-panel/profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile_show')]
    public function show(): Response
    {
        return $this->render('profile/show.html.twig', []);
    }

    #[Route('/{id}/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $myProfile = $userRepository->find($this->getUser()->getId());

        if ($user->getId() != $myProfile->getId()) dd('No se puede editar un usuario que no sea el tuyo');

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $lastPassword = $form->get("lastPassword")->getData();
            $newPassword = $form->get("password")->getData();
            $repeatNewPassword = $form->get("repeatPassword")->getData();

            if ($form->get("password")->getData()) {
                if (is_null($lastPassword)) {
                    $form->get('lastPassword')->addError(new FormError('La contraseña antigüa es errónea'));
                } 
                else if (! $passwordHasher->isPasswordValid($myProfile, $lastPassword)) {
                    $form->get('lastPassword')->addError(new FormError('La contraseña antigüa es errónea'));
                }

                if ($newPassword != $repeatNewPassword) {
                    $form->get('repeatPassword')->addError(new FormError('Las contraseñas deben coincidir'));
                }
            }

            if ($form->isValid()) {
                if ($form->get("password")->getData()) {
                    $hashedPassword = $passwordHasher->hashPassword(
                        $user,
                        $form->get("password")->getData(),
                    );
                    $user->setPassword($hashedPassword);
                }
    
                $userRepository->save($user, true);
    
                $this->addFlash('success', 'Tu perfil ha sido actualizado');
            }
        }

        if (count($form->getErrors()) > 0) { 
            $errorOrigin = $form->getErrors()[0]->getOrigin()->getName();
            $this->addFlash('error', $form->getErrors()[0]->getMessage());
        }

        return $this->renderForm('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'error_origin' => isset($errorOrigin) ? $errorOrigin : false,
        ]);
    }
}
