<?php

namespace App\Controller;

use App\Entity\Notification\NewUserNotification;
use App\Entity\Notification\UserBlockedNotification;
use App\Entity\User;
use App\Form\SearchUserType;
use App\Form\UserType;
use App\Repository\Notification\NewUserNotificationRepository;
use App\Repository\Notification\UserBlockedNotificationRepository;
use App\Repository\UserRepository;
use App\Services\Notification\NotificationChooser;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin-panel/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository, NotificationChooser $notificationChooser): Response
    {
        $user = new User();

        $form = $this->createForm(SearchUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $email = $form->get('email')->getData();
            $name = $form->get('name')->getData();
            $firstname = $form->get('firstname')->getData();
            $lastname = $form->get('lastname')->getData();
            $registeredFrom = $form->get('registeredFrom')->getData();
            $registeredTo = $form->get('registeredTo')->getData();
            $isActive = $form->get('isActive')->getData();
            
            $searchArray = [];

            if ($email) $searchArray['email'] = $email;
            if ($name) $searchArray['name'] = $name;
            if ($firstname) $searchArray['firstname'] = $firstname;
            if ($lastname) $searchArray['lastname'] = $lastname;
            if ($registeredFrom) $searchArray['creationDate'] >= $registeredFrom;
            if ($registeredTo) $searchArray['creationDate'] <= $registeredTo;
            if (! is_null($isActive)) $searchArray['isActive'] = $isActive;

            $users = $userRepository->findBy($searchArray);
        }

        $notifications = $this->getUser()->getNotifications();

        $notificationsArray = [];

        foreach ($notifications as $notification) {
            $notificationsArray[] = $notification;
        }

        usort($notificationsArray, function($a, $b) {
            return ($a->getCreationDate() > $b->getCreationDate()) ? -1 : 1;
        });

        $notificationsHtml = [];

        foreach ($notifications as $notification) {
            $notificationsHtml[] = $notificationChooser->getNotificationsBlock($notification);
        }

        return $this->render('user/index.html.twig', [
            'notifications' => $notificationsHtml,
            'users' => isset($users) ? $users : $userRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, NewUserNotificationRepository $newUserNotificationRepository): Response
    {
        $user = new User();

        $user->setRoles(["ROLE_USER"]);
        $user->setCreationDate(new DateTime());
        $user->setIsActive(true);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->get("password")->getData() != $form->get("repeatPassword")->getData()) {
                $form->get('password')->addError(new FormError('Las contraseñas deben coincidir'));
                $form->get('repeatPassword')->addError(new FormError('Las contraseñas deben coincidir'));
            }

            if ($form->isValid()) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $form->get("password")->getData(),
                );
                $user->setPassword($hashedPassword);
    
                $user->setRoles(["ROLE_USER"]);
                $user->setCreationDate(new DateTime());
                $user->setIsActive(true);
    
                $userRepository->save($user, true);

                $admins = $userRepository->findByRole("ROLE_ADMIN");

                $notification = new NewUserNotification();

                foreach ($admins as $admin) {
                    $notification->addUser($admin);   
                }
    
                $notification->setNewUser($user);
                $notification->setCreationDate(new DateTime());
                $notification->setIsViewed(false);

                $newUserNotificationRepository->save($notification, true);

                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        if (count($form->getErrors()) > 0) { 
            $errorOrigin = $form->getErrors()[0]->getOrigin()->getName();
            $this->addFlash('error', $form->getErrors()[0]->getMessage());
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'error_origin' => isset($errorOrigin) ? $errorOrigin : false,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'edit' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get("password")->getData()) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $form->get("password")->getData(),
                );
                $user->setPassword($hashedPassword);
            }

            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        if (count($form->getErrors()) > 0) $this->addFlash('error', $form->getErrors()[0]);

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/block', name: 'app_user_block', methods: ['POST'])]
    public function block(Request $request, User $user, UserRepository $userRepository, UserBlockedNotificationRepository $userBlockedNotificationRepository): Response
    {
        if ($this->isCsrfTokenValid('block'.$user->getId(), $request->request->get('_token'))) {
            if ($user->getIsActive()) {
                $user->setIsActive(false);

                $admins = $userRepository->findByRole("ROLE_ADMIN");

                $notification = new UserBlockedNotification();
    
                foreach ($admins as $admin) {
                    $notification->addUser($admin);   
                }
    
                $notification->setUserBlocked($user);
                $notification->setCreationDate(new DateTime());
                $notification->setIsViewed(false);
    
                $userBlockedNotificationRepository->save($notification, true);
            }
            else $user->setIsActive(true);

            $userRepository->save($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }



    // #[Route('/check-notifications/mark-as-viewed', name: 'app_user_check-notifications', methods: ['GET'])]
    // public function checkNotifications(NotificationRepository $notificationRepository): Response
    // {
    //     $noViewedNotifications = $notificationRepository->findBy(['user' => $this->getUser(), 'isViewed' => false]);

    //     foreach ($noViewedNotifications as $notification) {
    //         $notification->setIsViewed(true);
    //         $notificationRepository->save($notification, true);
    //     }

    //     return $this->json(["success" => true]);
    // }
}
