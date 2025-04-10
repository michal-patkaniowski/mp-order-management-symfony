<?php

declare(strict_types=1);

namespace App\Order\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class OrderItem
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    #[Assert\NotBlank(groups: [Order::GROUP_DETAILS])]
    #[Groups([Order::GROUP_DETAILS])]
    private string $id;

    #[ORM\Column(type: "string")]
    #[Assert\NotBlank(groups: [Order::GROUP_DETAILS])]
    #[Groups([Order::GROUP_DETAILS])]
    private string $productId;

    #[ORM\Column(type: "string")]
    #[Assert\NotBlank(groups: [Order::GROUP_DETAILS])]
    #[Groups([Order::GROUP_DETAILS])]
    private string $productName;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank(groups: [Order::GROUP_DETAILS])]
    #[Assert\Positive(groups: [Order::GROUP_DETAILS])]
    #[Groups([Order::GROUP_DETAILS])]
    private int $price;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank(groups: [Order::GROUP_DETAILS])]
    #[Assert\Positive(groups: [Order::GROUP_DETAILS])]
    #[Groups([Order::GROUP_DETAILS])]
    private int $quantity;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "items")]
    private Order $order;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): self
    {
        $this->productId = $productId;
        return $this;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;
        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): self
    {
        $this->order = $order;
        return $this;
    }
}
