<?php

namespace App\Model\Notifications;

use App\Entity\Notification\NewUserNotification;
use App\Entity\Notification\Notification;
use App\Entity\Notification\NotificationUser;
use Twig\Environment;

class NewUserNotificationBox {

    private Environment $twig;
    private NotificationUser $recivedNotification;

    public function __construct(NotificationUser $recivedNotification, Environment $twig)
    {
        $this->twig = $twig;
        $this->recivedNotification = $recivedNotification;
    }
    
    public function create(): Notification
    {
        return new NewUserNotification();
    }

    public function render(): string
    {
        return $this->twig->render("components/admin_panel/toolbar/notifications/newUserNotification.html.twig", [
            "receivedNotification" => $this->recivedNotification
        ]);
    }
}