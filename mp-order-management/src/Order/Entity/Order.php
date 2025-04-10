<?php

declare(strict_types=1);

namespace App\Order\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Order\Entity\OrderStatus;

#[ORM\Entity]
class Order
{
    public const GROUP_GENERAL = 'order_general';
    public const GROUP_DETAILS = 'order_details';

    private const VALID_STATUSES = ['new', 'paid', 'shipped', 'cancelled'];

    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    #[Assert\NotBlank(groups: [self::GROUP_GENERAL])]
    #[Groups([self::GROUP_GENERAL])]
    private string $id;

    #[ORM\Column(type: "datetime")]
    #[Assert\NotBlank(groups: [self::GROUP_GENERAL])]
    #[Groups([self::GROUP_GENERAL])]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: "string", enumType: OrderStatus::class)]
    #[Assert\NotBlank(groups: [self::GROUP_GENERAL])]
    #[Assert\Choice(choices: self::VALID_STATUSES, groups: [self::GROUP_GENERAL])]
    #[Groups([self::GROUP_GENERAL])]
    private string $status;

    #[ORM\OneToMany(mappedBy: "order", targetEntity: OrderItem::class, cascade: ["persist", "remove"])]
    #[Assert\Valid(groups: [self::GROUP_DETAILS])]
    #[Groups([self::GROUP_DETAILS])]
    private Collection $items;

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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function setItems(Collection $items): self
    {
        $this->items = $items;
        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }
}
