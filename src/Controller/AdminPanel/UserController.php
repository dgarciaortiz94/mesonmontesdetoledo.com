<?php

namespace App\Controller\AdminPanel;

use App\Entity\Notification\NewUserNotification;
use App\Entity\Notification\Notification;
use App\Entity\Notification\NotificationUser;
use App\Entity\Notification\UserBlockedNotification;
use App\Entity\User;
use App\Form\AdminPanel\User\SearchUserType;
use App\Form\AdminPanel\User\UserType;
use App\Repository\Notification\NewUserNotificationRepository;
use App\Repository\Notification\NotificationRepository;
use App\Repository\Notification\NotificationUserRepository;
use App\Repository\Notification\UserBlockedNotificationRepository;
use App\Repository\UserRepository;
use App\Services\Notification\NotificationFactory;
use DateTime;
use DateTimeImmutable;
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
    public function index(Request $request, UserRepository $userRepository): Response
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

        return $this->render('admin_panel/user/index.html.twig', [
            'users' => isset($users) ? $users : $userRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, NotificationUserRepository $notificationUserRepository, NewUserNotificationRepository $newUserNotificationRepository): Response
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
    
                if (is_null($form->get("imageFile")->getData())) $user->setImage('default.jpg');

                $user->setRoles(["ROLE_USER"]);
                $user->setCreationDate(new DateTime());
                $user->setIsActive(true);
    
                $userRepository->save($user, true);

                $admins = $userRepository->findByRole("ROLE_ADMIN");

                $notification = new NewUserNotification();

                $notification->setCreatedAt(new DateTimeImmutable());
                $notification->setNewUser($user);

                $newUserNotificationRepository->save($notification, true);

                foreach ($admins as $admin) {
                    $reportedNotification = new NotificationUser();
                    $reportedNotification->setNotification($notification);
                    $reportedNotification->setUser($admin);
                    $reportedNotification->setWatched(false);

                    $notificationUserRepository->save($reportedNotification, true);
                }

                $userRepository->save($user, true);

                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        if (count($form->getErrors()) > 0) { 
            $errorOrigin = $form->getErrors()[0]->getOrigin()->getName();
            $this->addFlash('error', $form->getErrors()[0]->getMessage());
        }

        return $this->renderForm('admin_panel/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'error_origin' => isset($errorOrigin) ? $errorOrigin : false,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin_panel/user/show.html.twig', [
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
            if ( (!is_null($form->get('imageFile')->getData())) && ($user->getImage() != "default.jpg") && file_exists("resources/media/admin/profiles/".$user->getImage())) unlink("resources/media/admin/profiles/".$user->getImage());

            if ($form->get("password")->getData()) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $form->get("password")->getData(),
                );
                $user->setPassword($hashedPassword);
            }

            $user->setUpdatedAt(new DateTimeImmutable());

            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        if (count($form->getErrors()) > 0) $this->addFlash('error', $form->getErrors()[0]);

        return $this->renderForm('admin_panel/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/block', name: 'app_user_block', methods: ['POST'])]
    public function block(Request $request, User $user, UserRepository $userRepository, UserBlockedNotificationRepository $userBlockedNotificationRepository, NotificationUserRepository $notificationUserRepository): Response
    {
        if ($this->isCsrfTokenValid('block'.$user->getId(), $request->request->get('_token'))) {
            if ($user->getIsActive()) {
                $user->setIsActive(false);

                $admins = $userRepository->findByRole("ROLE_ADMIN");

                $notification = new UserBlockedNotification();
                $notification->setUserBlocked($user);
                $notification->setCreatedAt(new DateTimeImmutable());
    
                $userBlockedNotificationRepository->save($notification, true);
    
                foreach ($admins as $admin) {
                    // if ($admin != $this->getUser()) {
                    $reportedNotification = new NotificationUser();
                    $reportedNotification->setNotification($notification);
                    $reportedNotification->setUser($admin);
                    $reportedNotification->setWatched(false);

                    $notificationUserRepository->save($reportedNotification, true);
                    // }
                }
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

            if ($user->getImage() != "default.jpg") unlink("resources/media/admin/profiles/".$user->getImage());
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
