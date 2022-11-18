<?php

namespace App\Model\Notifications;

use App\Entity\Notification\NotificationInterface;
use App\Entity\Notification\UserBlockedNotification;
use Twig\Environment;

class UserBlockedNotificationBox {

    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }
    
    public function create(): NotificationInterface
    {
        return new UserBlockedNotification();
    }

    public function render(NotificationInterface $notification): string
    {
        return $this->twig->render("components/admin_panel/toolbar/notifications/UserBlockedNotification.html.twig", [
            "notification" => $notification
        ]);
    }
}