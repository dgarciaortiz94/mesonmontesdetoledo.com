<?php

namespace App\Entity;

use App\Entity\Notification\NewUserNotification;
use App\Entity\Notification\Notification;
use App\Entity\Notification\UserBlockedNotification;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
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

    #[ORM\ManyToMany(targetEntity: NewUserNotification::class, mappedBy: 'user')]
    private Collection $newUserNotifications;

    #[ORM\ManyToMany(targetEntity: UserBlockedNotification::class, mappedBy: 'user')]
    private Collection $userBlockedNotifications;

    public function __construct()
    {
        $this->newUserNotifications = new ArrayCollection();
        $this->userBlockedNotifications = new ArrayCollection();
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
            // $this->updatedAt = new \DateTimeImmutable();
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

    public function getNotifications(): Collection 
    {
        $collection = new ArrayCollection();

        foreach ($this->newUserNotifications as $newUserNotification) {
            $collection->add($newUserNotification);
        }

        foreach ($this->userBlockedNotifications as $userBlockedNotification) {
            $collection->add($userBlockedNotification);
        }

        return $collection;
    }

    /**
     * @return Collection<int, NewUserNotification>
     */
    public function getNewUserNotifications(): Collection
    {
        return $this->newUserNotifications;
    }

    public function addNewUserNotification(NewUserNotification $newUserNotification): self
    {
        if (!$this->newUserNotifications->contains($newUserNotification)) {
            $this->newUserNotifications->add($newUserNotification);
            $newUserNotification->addUser($this);
        }

        return $this;
    }

    public function removeNewUserNotification(NewUserNotification $newUserNotification): self
    {
        if ($this->newUserNotifications->removeElement($newUserNotification)) {
            $newUserNotification->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, UserBlockedNotification>
     */
    public function getUserBlockedNotifications(): Collection
    {
        return $this->userBlockedNotifications;
    }

    public function addUserBlockedNotification(UserBlockedNotification $userBlockedNotification): self
    {
        if (!$this->userBlockedNotifications->contains($userBlockedNotification)) {
            $this->userBlockedNotifications->add($userBlockedNotification);
            $userBlockedNotification->addUser($this);
        }

        return $this;
    }

    public function removeUserBlockedNotification(UserBlockedNotification $userBlockedNotification): self
    {
        if ($this->userBlockedNotifications->removeElement($userBlockedNotification)) {
            $userBlockedNotification->removeUser($this);
        }

        return $this;
    }

}
