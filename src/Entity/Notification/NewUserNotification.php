<?php

namespace App\Entity\Notification;

use App\Entity\User;
use App\Repository\Notification\NewUserNotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewUserNotificationRepository::class)]
class NewUserNotification extends Notification
{
    private ?string $label = "Se ha registrado un nuevo usuario";

    #[ORM\OneToOne(inversedBy: 'newUserNotification', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $newUser = null;

    public function getNewUser(): ?User
    {
        return $this->newUser;
    }

    public function setNewUser(User $newUser): self
    {
        $this->newUser = $newUser;

        return $this;
    }

    


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
}
