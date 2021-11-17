<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read:Product:collection"}},
 *     denormalizationContext={"groups"={"write:Product:collection"}},
 *     collectionOperations={
 *          "get",
 *          "post"={
 *                  "security" = "is_granted('ROLE_ADMIN')",
 *                  "security_message" = "Only admins can add products.",
 *           }
 *      },
 *     itemOperations={
 *          "get"={
 *                 "normalization_context" = {"groups"={"read:Product:collection", "read:Product:item"}}
 *           },
 *           "put"={
 *                 "security" = "is_granted('ROLE_ADMIN')",
 *                 "security_message" = "Only admins can replace products.",
 *           },
 *            "patch"={
 *                 "security" = "is_granted('ROLE_ADMIN')",
 *                  "security_message" = "Only admins can update products.",
 *           },
 *            "delete"={
 *                 "security" = "is_granted('ROLE_ADMIN')",
 *                  "security_message" = "Only admins can delete products.",
 *           }
 *      }
 * )
 * @ApiFilter(RangeFilter::class, properties={"price"})
 * @ApiFilter(OrderFilter::class, properties={"price"}, arguments={"orderParameterName" : "order"})
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @UniqueEntity(fields={"model"}, message="This value ( {{ value }} ) is already used.")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:Product:collection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:Product:collection", "write:Product:collection"})
     * @Assert\NotBlank(message="This value should not be blank.")
     */
    private $model;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read:Product:item", "write:Product:collection"})
     * @Assert\NotBlank(message="This value should not be blank.")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups({"read:Product:item", "write:Product:collection"})
     * @Assert\NotBlank(message="This value should not be blank.")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read:Product:item", "write:Product:collection"})
     * @Assert\NotBlank(message="This value should not be blank.")
     */
    private $quantity;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups("read:Product:item")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups("read:Product:item")
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="product")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read:Product:collection", "write:Product:collection"})
     */
    private $brand;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

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

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }
}
