<?php

declare(strict_types=1);

namespace App\Order\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Order\Entity\OrderStatus;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: '`order_table`')]
class Order
{
    public const GROUP_GENERAL = 'order_general';
    public const GROUP_ITEMS = 'order_items';
    public const GROUP_STATUS = 'order_status';

    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    #[Assert\NotBlank(groups: [self::GROUP_GENERAL])]
    #[Groups([self::GROUP_GENERAL, self::GROUP_STATUS])]
    private string $id;

    #[ORM\Column(type: "datetime")]
    #[Assert\NotBlank(groups: [self::GROUP_GENERAL])]
    #[Groups([self::GROUP_GENERAL])]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: "string", enumType: OrderStatus::class)]
    #[Assert\NotBlank(groups: [self::GROUP_GENERAL])]
    #[Groups([self::GROUP_GENERAL, self::GROUP_STATUS])]
    private OrderStatus $status;

    private ?OrderStatus $previousStatus = null;

    #[ORM\OneToMany(mappedBy: "order", targetEntity: OrderItem::class, cascade: ["persist", "remove"])]
    #[Assert\Valid(groups: [self::GROUP_ITEMS])]
    #[Groups([self::GROUP_ITEMS])]
    /**
    * @var Collection|OrderItem[]
     */
    private Collection|array $items;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank(groups: [self::GROUP_GENERAL])]
    #[Assert\PositiveOrZero(groups: [self::GROUP_GENERAL])]
    #[Groups([self::GROUP_GENERAL])]
    private int $total;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function setStatus(OrderStatus $status): self
    {
        $this->previousStatus = $this->status ?? null;
        $this->status = $status;
        return $this;
    }

    public function getPreviousStatus(): ?OrderStatus
    {
        return $this->previousStatus;
    }

    public function getItems(): Collection|array
    {
        return $this->items;
    }

    public function setItems(Collection|array $items): self
    {
        $this->items = $items;
        return $this;
    }

    public function getTotal(): int
    {
        $this->updateTotal();
        return $this->total;
    }

    public function updateTotal(): void
    {
        $this->total = array_reduce(
            $this->items instanceof Collection ? $this->items->toArray() : $this->items,
            static fn($carry, OrderItem $item): int => $carry + $item->getPrice() * $item->getQuantity(),
            0
        );
    }
}
