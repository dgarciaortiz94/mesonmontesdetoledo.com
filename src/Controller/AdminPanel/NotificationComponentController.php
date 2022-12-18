<?php

namespace App\Controller\AdminPanel;

use App\Repository\Notification\NotificationUserRepository;
use App\Services\Notification\NotificationComponentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin-panel/notification-component')]
class NotificationComponentController extends AbstractController
{
    #[Route('/', name: 'app_notification_component')]
    public function getNotificationComponents(NotificationComponentManager $notificationFactory): Response
    {
        $receivedNotifications = $this->getUser()->getReceivedNotifications();
        $receivedNotifications = $receivedNotifications->getIterator();

        $notificationComponents = [];

        foreach ($receivedNotifications as $receivedNotification) {
            $notificationComponents[] = $notificationFactory->getNotificationComponent($receivedNotification);
        }

        return $this->render('components/admin_panel/toolbar/notifications/_notifications.html.twig', [
            'receivedNotifications' => $notificationComponents,
        ]);
    }

    #[Route('/set-as-watched', name: 'app_user_check-notifications', methods: ['GET'])]
    public function checkNotifications(NotificationUserRepository $notificationUserRepository): Response
    {
        $noWatchedNotifications = $notificationUserRepository->findBy(['user' => $this->getUser(), 'watched' => false]);

        foreach ($noWatchedNotifications as $receivedNotification) {
            $receivedNotification->setWatched(true);
            $notificationUserRepository->save($receivedNotification, true);
        }

        return $this->json(["success" => true]);
    }
}
