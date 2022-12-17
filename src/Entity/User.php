<?php

namespace App\Entity;

use App\Entity\Notification\NewUserNotification;
use App\Entity\Notification\NotificationUser;
use App\Entity\Notification\UserBlockedNotification;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface, Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $firstname = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    private ?int $imageSize = null;

    #[Vich\UploadableField(mapping: 'profiles', fileNameProperty: 'image', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(mappedBy: 'newUser', cascade: ['persist', 'remove'])]
    private ?NewUserNotification $newUserNotification = null;

    #[ORM\OneToMany(mappedBy: 'userBlocked', targetEntity: UserBlockedNotification::class, orphanRemoval: true)]
    private Collection $userBlockedNotifications;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: NotificationUser::class, orphanRemoval: true)]
    private Collection $receivedNotifications;

    public function __construct()
    {
        $this->userBlockedNotifications = new ArrayCollection();
        $this->newUserNotifications = new ArrayCollection();
        $this->receivedNotifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    // public function getRepeatPassword()
    // {
    //     return $this->repeatPassword;
    // }

    // public function setRepeatPassword($repeatPassword): self
    // {
    //     $this->repeatPassword = $repeatPassword;

    //     return $this;
    // }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getImageSize()
    {
        return $this->imageSize;
    }

    public function setImageSize($imageSize)
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->name,
            $this->firstname,
            $this->lastname,
            $this->email,
            $this->password,
            $this->image,
            $this->receivedNotifications,
            $this->roles,
            $this->isActive,
            $this->updatedAt,
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->name,
            $this->firstname,
            $this->lastname,
            $this->email,
            $this->password,
            $this->image,
            $this->receivedNotifications,
            $this->roles,
            $this->isActive,
            $this->updatedAt,
        ) = unserialize($serialized);
    }

    /**
     * @return Collection<int, NotificationUser>
     */
    public function getReceivedNotifications(): Collection
    {
        return $this->receivedNotifications;
    }

    public function addReceivedNotification(NotificationUser $receivedNotification): self
    {
        if (!$this->receivedNotifications->contains($receivedNotification)) {
            $this->receivedNotifications->add($receivedNotification);
            $receivedNotification->setUser($this);
        }

        return $this;
    }

    public function removeReceivedNotification(NotificationUser $receivedNotification): self
    {
        if ($this->receivedNotifications->removeElement($receivedNotification)) {
            // set the owning side to null (unless already changed)
            if ($receivedNotification->getUser() === $this) {
                $receivedNotification->setUser(null);
            }
        }

        return $this;
    }
}
