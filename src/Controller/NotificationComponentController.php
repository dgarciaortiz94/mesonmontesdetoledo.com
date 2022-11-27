<?php

namespace App\Controller;

use App\Services\Notification\NotificationComponentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationComponentController extends AbstractController
{
    #[Route('/notification-component', name: 'app_notification_component')]
    public function getNotificationComponents(NotificationComponentManager $notificationFactory): Response
    {
        $notifications = $this->getUser()->getNotifications();
        $notifications = $notifications->getIterator();

        $notifications->uasort(function($a, $b) {
            return ($a->getCreationDate() > $b->getCreationDate()) ? -1 : 1;
        });

        $notificationComponents = [];

        foreach ($notifications as $notification) {
            $notificationComponents[] = $notificationFactory->getNotificationComponent($notification);
        }

        return $this->render('components/admin_panel/toolbar/notifications/_notifications.html.twig', [
            'notifications' => $notificationComponents,
        ]);
    }
}
