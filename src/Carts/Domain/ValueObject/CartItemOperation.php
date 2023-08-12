<?php

declare(strict_types=1);

namespace App\Carts\Domain\ValueObject;

use App\Products\Domain\Product;
use App\Shared\Utils;
use DateTimeImmutable;
use InvalidArgumentException;

final class CartItemOperation
{
    public function __construct(
        protected readonly string $type,
        protected readonly Product $product,
        protected readonly ?int $quantity,
        protected readonly DateTimeImmutable $dateTime
    ) {
        $this->ensureIsValidType($type);
    }

    public static function create(
        string $type,
        Product $product,
        ?int $quantity,
        DateTimeImmutable $dateTime
    ): self {
        return new self(
            type: $type,
            product: $product,
            quantity: $quantity,
            dateTime: $dateTime
        );
    }

    public function type(): string
    {
        return $this->type;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function quantity(): ?int
    {
        return $this->quantity;
    }

    public function dateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }

    private function ensureIsValidType(string $type): void
    {
        if (!in_array($type, OperationType::values())) {
            throw new InvalidArgumentException(sprintf(
                "'%s' does not allow the type '%s'.",
                Utils::extractClassName(CartItemOperation::class),
                $type
            ));
        }
    }
}
