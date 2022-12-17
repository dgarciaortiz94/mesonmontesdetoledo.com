<?php

namespace App\Entity\Notification;

use App\Entity\User;
use App\Repository\Notification\NotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'type', type: 'string')]
#[DiscriminatorMap(['notification' => Notification::class, 'newUserNotification' => NewUserNotification::class, 'userBlockedNotification' => UserBlockedNotification::class])]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    private ?string $label = "Notificación";

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'notification', targetEntity: NotificationUser::class, orphanRemoval: true)]
    private Collection $reportedUsers;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->reportedUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, NotificationUser>
     */
    public function getReportedUsers(): Collection
    {
        return $this->reportedUsers;
    }

    public function addReportedUser(NotificationUser $reportedUser): self
    {
        if (!$this->reportedUsers->contains($reportedUser)) {
            $this->reportedUsers->add($reportedUser);
            $reportedUser->setNotification($this);
        }

        return $this;
    }

    public function removeReportedUser(NotificationUser $reportedUser): self
    {
        if ($this->reportedUsers->removeElement($reportedUser)) {
            // set the owning side to null (unless already changed)
            if ($reportedUser->getNotification() === $this) {
                $reportedUser->setNotification(null);
            }
        }

        return $this;
    }
}
