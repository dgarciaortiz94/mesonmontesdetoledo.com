<?php

namespace App\Model\Notifications;

use App\Entity\Notification\NotificationInterface;
use App\Entity\Notification\UserBlockedNotification;
use Twig\Environment;

class UserBlockedNotificationBox {

    private Environment $twig;
    private NotificationInterface $notification;

    public function __construct(NotificationInterface $notification, Environment $twig)
    {
        $this->twig = $twig;
        $this->notification = $notification;
    }
    
    public function create(): NotificationInterface
    {
        return new UserBlockedNotification();
    }

    public function render(): string
    {
        return $this->twig->render("components/admin_panel/toolbar/notifications/UserBlockedNotification.html.twig", [
            "notification" => $this->notification
        ]);
    }
}