<?php

namespace App\Model\Notifications;

use App\Entity\Notification\NewUserNotification;
use App\Entity\Notification\Notification;
use Twig\Environment;

class NewUserNotificationBox {

    private Environment $twig;
    private Notification $notification;

    public function __construct(Notification $notification, Environment $twig)
    {
        $this->twig = $twig;
        $this->notification = $notification;
    }
    
    public function create(): Notification
    {
        return new NewUserNotification();
    }

    public function render(): string
    {
        return $this->twig->render("components/admin_panel/toolbar/notifications/newUserNotification.html.twig", [
            "notification" => $this->notification
        ]);
    }
}