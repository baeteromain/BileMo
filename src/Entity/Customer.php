<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read:Customer:collection"}},
 *     denormalizationContext={"groups"={"write:Customer:collection"}},
 *     collectionOperations={
 *          "get",
 *          "post"={
 *                  "security" = "is_granted('ROLE_ADMIN')",
 *                  "security_message" = "Only admins can add customers.",
 *           }
 *      },
 *     itemOperations={
 *          "get",
 *           "put"={
 *                 "security" = "is_granted('ROLE_ADMIN')",
 *                 "security_message" = "Only admins can replace customers.",
 *           },
 *            "patch"={
 *                 "security" = "is_granted('ROLE_ADMIN')",
 *                  "security_message" = "Only admins can update customers.",
 *           },
 *            "delete"={
 *                 "security" = "is_granted('ROLE_ADMIN')",
 *                  "security_message" = "Only admins can delete customers.",
 *           }
 *      }
 * )
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"name"}, message="This value ( {{ value }} ) is already used.")
 */
class Customer implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:Customer:collection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"read:Customer:collection", "write:Customer:collection"})
     * @Assert\NotBlank(message="This value should not be blank.")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"write:Customer:collection"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"read:Customer:collection", "write:Customer:collection"})
     * @Assert\NotBlank(message="This value should not be blank.")
     */
    private $name;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"read:Customer:collection"})
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="customer", orphanRemoval=true)
     * @Groups({"read:Customer:collection", "write:Customer:collection"})
     */
    private $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
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

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @ORM\PrePersist()
     * @return $this
     */
    public function setCreatedAt(): self
    {
        $this->created_at = new \DateTimeImmutable(null, new \DateTimeZone('Europe/Paris'));

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setCustomer($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCustomer() === $this) {
                $user->setCustomer(null);
            }
        }

        return $this;
    }
}
