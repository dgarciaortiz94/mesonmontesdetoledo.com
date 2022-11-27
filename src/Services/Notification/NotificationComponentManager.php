<?php

namespace App\Services\Notification;

use App\Entity\Notification\NotificationInterface;
use App\Model\Notifications\NewUserNotificationBox;
use App\Model\Notifications\UserBlockedNotificationBox;
use Exception;
use Twig\Environment;

class NotificationComponentManager
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getNotificationComponent($notification)
    {
        $notificationBox = false;

        if (get_class($notification) == "App\Entity\Notification\NewUserNotification") {
            $notificationBox = new NewUserNotificationBox($notification, $this->twig);
        } else if (get_class($notification) == "App\Entity\Notification\UserBlockedNotification") {
            $notificationBox = new UserBlockedNotificationBox($notification, $this->twig);
        } else {
            throw new Exception(get_class($notification) . " doesn't exist in NotificationBuilder::getNotificationsBlock");
        }

        return $notificationBox;
    }
}