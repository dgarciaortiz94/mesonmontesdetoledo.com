<?php

namespace App\Model\Notifications;

use App\Entity\Notification\NotificationInterface;

interface NotificationBoxInterface {
    
    public function create(): NotificationInterface;

    public function render(NotificationInterface $notification): string;

}