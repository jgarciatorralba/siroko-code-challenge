<?php

declare(strict_types=1);

namespace App\Carts\Domain;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Aggregate\Timestampable;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Utils;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Cart extends AggregateRoot
{
    use Timestampable;

    /**
     * @var Collection<int, CartItem>
     */
    private Collection $items;

    public function __construct(
        private Uuid $id,
        private ?float $subtotal,
        private ?DateTimeImmutable $confirmedAt,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ) {
        $this->items = new ArrayCollection();

        $this->updateCreatedAt($createdAt);
        $this->updateUpdatedAt($updatedAt);
    }

    public static function create(
        Uuid $id,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ): self {
        return new self(
            id: $id,
            subtotal: null,
            confirmedAt: null,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function subtotal(): ?float
    {
        return $this->subtotal;
    }

    public function confirmedAt(): ?DateTimeImmutable
    {
        return $this->confirmedAt;
    }

    public function updateSubtotal(?float $subtotal): void
    {
        $this->subtotal = $subtotal;
    }

    public function updateConfirmedAt(?DateTimeImmutable $confirmedAt): void
    {
        $this->confirmedAt = $confirmedAt;
    }

    /**
     * @return Collection<int, CartItem>
     */
    public function items(): Collection
    {
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
        }
    }

    public function getItemById(Uuid $id): ?CartItem
    {
        /** @var CartItem $item */
        foreach ($this->items as $item) {
            if ($item->id()->equals($id)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(bool $isNestedArray = false): array
    {
        $cartArray = [
            'id' => $this->id->value(),
            'items' => [],
            'num_products' => 0,
            'subtotal' => $this->subtotal,
            'confirmed_at' => !empty($this->confirmedAt) ? Utils::dateToString($this->confirmedAt) : null,
            'created_at' => Utils::dateToString($this->createdAt),
            'updated_at' => Utils::dateToString($this->updatedAt)
        ];

        if ($isNestedArray) {
            unset($cartArray['items'], $cartArray['num_products']);
        } else {
            $cartArray['items'] = array_map(
                fn (CartItem $item) => $item->toArray(true),
                $this->items->toArray()
            );
            $cartArray['num_products'] = array_reduce(
                $this->items->toArray(),
                function (int $carry, CartItem $item) {
                    $carry += $item->quantity();
                    return $carry;
                },
                0
            );
        }

        return $cartArray;
    }
}
