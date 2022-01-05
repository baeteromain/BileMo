<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read:User:collection"}},
 *     denormalizationContext={"groups"={"write:User:collection"}},
 *     collectionOperations={
 *          "get","post"
 *      },
 *     itemOperations={
 *          "get"={
 *                  "security" = "is_granted('ROLE_ADMIN') or object.getCustomer() == user",
 *                  "security_message" = "Only view your own users",
 *           },
 *           "put"={
 *                 "security" = "is_granted('ROLE_ADMIN') or object.getCustomer() == user",
 *                 "security_message" = "Can replace only your own users.",
 *           },
 *            "patch"={
 *                 "security" = "is_granted('ROLE_ADMIN') or object.getCustomer() == user",
 *                  "security_message" = "Can update only your own users.",
 *           },
 *            "delete"={
 *                 "security" = "is_granted('ROLE_ADMIN') or object.getCustomer() == user",
 *                  "security_message" = "Can delete only your own users.",
 *           }
 *      }
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiFilter(OrderFilter::class, properties={"created_at"}, arguments={"orderParameterName" : "order"})
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"email"}, message="This value ( {{ value }} ) is already used.")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:User:collection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"read:User:collection", "write:User:collection"})
     * @Assert\NotBlank(message="This value should not be blank.")
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"write:User:collection"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"read:User:collection", "write:User:collection"})
     * @Assert\NotBlank(message="This value should not be blank.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"read:User:collection", "write:User:collection"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"read:User:collection", "write:User:collection"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"read:User:collection"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"read:User:collection"})
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Groups({"read:User:collection", "write:User:collection"})
     */
    private $zip_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read:User:collection", "write:User:collection"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"read:User:collection", "write:User:collection"})
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"read:User:collection", "write:User:collection"})
     */
    private $phone_number;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"read:User:collection", "write:User:collection"})
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="user")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->username;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * @ORM\PreUpdate()
     * @return $this
     */
    public function setUpdatedAt(): self
    {
        $this->updated_at = new \DateTimeImmutable(null, new \DateTimeZone('Europe/Paris'));

        return $this;
    }

    public function getZip_Code(): ?string
    {
        return $this->zip_code;
    }

    public function setZip_Code(?string $zip_code): self
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone_Number(): ?string
    {
        return $this->phone_number;
    }

    public function setPhone_Number(?string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
