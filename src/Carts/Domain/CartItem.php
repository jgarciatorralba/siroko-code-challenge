<?php

namespace App\Carts\Domain;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Aggregate\Timestampable;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Utils;
use App\Products\Domain\Product;
use DateTimeImmutable;

class CartItem extends AggregateRoot
{
    use Timestampable;

    public function __construct(
        private Uuid $id,
        private readonly Cart $cart,
        private readonly Product $product,
        private int $quantity,
        private ?float $subtotal,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ) {
        $this->updateCreatedAt($createdAt);
        $this->updateUpdatedAt($updatedAt);
    }

    public static function create(
        Uuid $id,
        Cart $cart,
        Product $product,
        int $quantity,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ): self {
        return new self(
            id: $id,
            cart: $cart,
            product: $product,
            quantity: $quantity,
            subtotal: null,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function cart(): Cart
    {
        return $this->cart;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function subtotal(): ?float
    {
        return $this->subtotal;
    }

    public function updateQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function updateSubtotal(?float $subtotal): void
    {
        $this->subtotal = $subtotal;
    }

    public function calculateSubtotal(): float
    {
        return floatval($this->quantity() * $this->product()->price());
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(bool $isNestedArray = false): array
    {
        $carItemArray = [
            'id' => $this->id->value(),
            'cart' => [],
            'product' => $this->product->toArray(),
            'quantity' => $this->quantity,
            'subtotal' => $this->subtotal,
            'created_at' => Utils::dateToString($this->createdAt),
            'updated_at' => Utils::dateToString($this->updatedAt)
        ];

        if ($isNestedArray) {
            unset($carItemArray['cart']);
        } else {
            $carItemArray['cart'] = $this->cart->toArray(true);
        }

        return $carItemArray;
    }
}
