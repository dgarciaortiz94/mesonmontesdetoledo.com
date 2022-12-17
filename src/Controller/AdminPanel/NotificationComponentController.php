<?php

namespace App\Controller\AdminPanel;

use App\Services\Notification\NotificationComponentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationComponentController extends AbstractController
{
    #[Route('/notification-component', name: 'app_notification_component')]
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
}
