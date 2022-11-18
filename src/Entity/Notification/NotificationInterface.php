<?php

namespace App\Entity\Notification;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;

interface NotificationInterface
{
    public function getId(): ?int;

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection;

    public function addUser(User $user): self;

    public function removeUser(User $user): self;

    public function getCreationDate(): ?\DateTimeInterface;

    public function setCreationDate(\DateTimeInterface $creationDate): self;

    public function isIsViewed(): ?bool;

    public function setIsViewed(bool $isViewed): self;

    public function getLabel(): string;

    public function setLabel(string $label): self;
}
