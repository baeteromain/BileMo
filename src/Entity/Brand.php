<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read:Brand:collection"}},
 *     denormalizationContext={"groups"={"write:Brand:collection"}},
 *     collectionOperations={
 *          "get",
 *          "post"={
 *                  "security" = "is_granted('ROLE_ADMIN')",
 *                  "security_message" = "Only admins can add brands.",
 *           }
 *      },
 *     itemOperations={
 *          "get"={
 *                 "normalization_context" = {"groups"={"read:Brand:collection", "read:Brand:item"}}
 *           },
 *           "put"={
 *                 "security" = "is_granted('ROLE_ADMIN')",
 *                 "security_message" = "Only admins can replace brands.",
 *           },
 *            "patch"={
 *                 "security" = "is_granted('ROLE_ADMIN')",
 *                  "security_message" = "Only admins can update brands.",
 *           },
 *            "delete"={
 *                 "security" = "is_granted('ROLE_ADMIN')",
 *                  "security_message" = "Only admins can delete brands.",
 *           }
 *      }
 * )
 * @ORM\Entity(repositoryClass=BrandRepository::class)
 * @UniqueEntity(fields={"name"}, message="This value ( {{ value }} ) is already used.")
 */
class Brand
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:Brand:collection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:Brand:collection", "write:Brand:collection", "read:Brand:item"})
     * @Assert\NotBlank(message="This value should not be blank.")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="brand", orphanRemoval=true)
     * @Groups({"read:Product:collection", "write:Product:collection", "read:Product:item"})
     */
    private $product;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setBrand($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getBrand() === $this) {
                $product->setBrand(null);
            }
        }

        return $this;
    }
}
