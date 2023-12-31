<?php

declare(strict_types=1);

namespace App\Products\Domain;

use App\Carts\Domain\CartItem;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Aggregate\Timestampable;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Utils;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Product extends AggregateRoot
{
    use Timestampable;

    /**
     * @var Collection<int, CartItem>
     */
    private Collection $cartItems;

    public function __construct(
        private Uuid $id,
        private string $name,
        private float $price,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ) {
        $this->cartItems = new ArrayCollection();

        $this->updateCreatedAt($createdAt);
        $this->updateUpdatedAt($updatedAt);
    }

    public static function create(
        Uuid $id,
        string $name,
        float $price,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ): self {
        return new self(
            id: $id,
            name: $name,
            price: $price,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function updateName(string $name): void
    {
        $this->name = $name;
    }

    public function updatePrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return Collection<int, CartItem>
     */
    public function cartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $item): void
    {
        if (!$this->cartItems->contains($item)) {
            $this->cartItems->add($item);
        }
    }

    /**
     * @return array<string, string|float|DateTimeImmutable>
     */
    public function toArray(bool $isNestedArray = false): array
    {
        $productArray = [
            'id' => $this->id->value(),
            'name' => $this->name,
            'price' => $this->price,
            'created_at' => Utils::dateToString($this->createdAt),
            'updated_at' => Utils::dateToString($this->updatedAt)
        ];

        return $productArray;
    }
}
