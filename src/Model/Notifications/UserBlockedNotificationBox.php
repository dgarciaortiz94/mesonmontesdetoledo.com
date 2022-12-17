<?php

namespace App\Model\Notifications;

use App\Entity\Notification\Notification;
use App\Entity\Notification\NotificationInterface;
use App\Entity\Notification\NotificationUser;
use App\Entity\Notification\UserBlockedNotification;
use Twig\Environment;

class UserBlockedNotificationBox {

    private Environment $twig;
    private NotificationUser $recivedNotification;

    public function __construct(NotificationUser $recivedNotification, Environment $twig)
    {
        $this->twig = $twig;
        $this->recivedNotification = $recivedNotification;
    }
    
    public function create(): Notification
    {
        return new UserBlockedNotification();
    }

    public function render(): string
    {
        return $this->twig->render("components/admin_panel/toolbar/notifications/userBlockedNotification.html.twig", [
            "receivedNotification" => $this->recivedNotification
        ]);
    }
}