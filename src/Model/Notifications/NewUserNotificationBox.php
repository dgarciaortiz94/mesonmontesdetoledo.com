<?php

namespace App\Model\Notifications;

use App\Entity\Notification\NewUserNotification;
use App\Entity\Notification\NotificationInterface;
use Twig\Environment;

class NewUserNotificationBox {

    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }
    
    public function create(): NotificationInterface
    {
        return new NewUserNotification();
    }

    public function render(NotificationInterface $notification): string
    {
        return $this->twig->render("components/admin_panel/toolbar/notifications/newUserNotification.html.twig", [
            "notification" => $notification
        ]);
    }
}