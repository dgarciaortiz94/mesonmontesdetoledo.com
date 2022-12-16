<?php

namespace App\Model\Notifications;

use App\Entity\Notification\Notification;

interface NotificationBoxInterface {
    
    public function create(): Notification;

    public function render(Notification $notification): string;

}