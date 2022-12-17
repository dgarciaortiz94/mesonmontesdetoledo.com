<?php

namespace App\Entity\Notification;

use App\Entity\User;
use App\Repository\Notification\NotificationUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationUserRepository::class)]
class NotificationUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reportedUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Notification $notification = null;

    #[ORM\ManyToOne(inversedBy: 'receivedNotifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $watched = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(?Notification $notification): self
    {
        $this->notification = $notification;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isWatched(): ?bool
    {
        return $this->watched;
    }

    public function setWatched(bool $watched): self
    {
        $this->watched = $watched;

        return $this;
    }
}
