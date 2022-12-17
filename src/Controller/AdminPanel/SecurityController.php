<?php

namespace App\Controller\AdminPanel;

use App\Entity\User;
use App\Form\AdminPanel\Profile\SecurityType;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin-panel/security')]
class SecurityController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_admin_panel_security_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $myProfile = $userRepository->find($this->getUser()->getId());

        if ($user->getId() != $myProfile->getId()) dd('No se puede editar un usuario que no sea el tuyo');

        $form = $this->createForm(SecurityType::class, $user);
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

                $user->setUpdatedAt(new DateTimeImmutable());
    
                $userRepository->save($user, true);
    
                $this->addFlash('success', 'Tu contraseña ha sido actualizada');
            }
        }

        if (count($form->getErrors()) > 0) { 
            $errorOrigin = $form->getErrors()[0]->getOrigin()->getName();
            $this->addFlash('error', $form->getErrors()[0]->getMessage());
        }

        return $this->renderForm('admin_panel/security/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'error_origin' => isset($errorOrigin) ? $errorOrigin : false,
        ]);
    }
}
