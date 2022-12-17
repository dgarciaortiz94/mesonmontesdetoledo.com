<?php

namespace App\Services\Notification;

use App\Entity\Notification\NotificationInterface;
use App\Entity\Notification\NotificationUser;
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

    public function getNotificationComponent(NotificationUser $receivedNotification)
    {
        $notificationBox = false;

        if (get_class($receivedNotification->getNotification()) == "App\Entity\Notification\NewUserNotification") {
            $notificationBox = new NewUserNotificationBox($receivedNotification, $this->twig);
        } else if (get_class($receivedNotification->getNotification()) == "App\Entity\Notification\UserBlockedNotification") {
            $notificationBox = new UserBlockedNotificationBox($receivedNotification, $this->twig);
        } else {
            throw new Exception(get_class($receivedNotification) . " doesn't exist in NotificationBuilder::getNotificationsBlock");
        }

        return $notificationBox;
    }
}