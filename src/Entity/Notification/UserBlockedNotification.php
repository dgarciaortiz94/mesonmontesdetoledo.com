<?php

namespace App\Entity\Notification;

use App\Entity\User;
use App\Repository\Notification\UserBlockedNotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserBlockedNotificationRepository::class)]
class UserBlockedNotification extends Notification
{
    private ?string $label = "Un usuario ha sido bloqueado";

    #[ORM\ManyToOne(inversedBy: 'userBlockedNotifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userBlocked = null;

    /**
     * Get the value of label
     */ 
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set the value of label
     *
     * @return  self
     */ 
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function getUserBlocked(): ?User
    {
        return $this->userBlocked;
    }

    public function setUserBlocked(?User $userBlocked): self
    {
        $this->userBlocked = $userBlocked;

        return $this;
    }
}
