<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\Domain;

use App\Carts\Domain\Cart;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use DateTimeImmutable;

final class CartMother
{
    public static function create(
        ?Uuid $id = null,
        ?float $subtotal = null,
        ?bool $isConfirmed = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null
    ): Cart {
        return new Cart(
            id: $id ?? FakeValueGenerator::uuid(),
            subtotal: $subtotal ?? FakeValueGenerator::float(0, 100),
            isConfirmed: $isConfirmed ?? FakeValueGenerator::boolean(),
            createdAt: $createdAt ?? FakeValueGenerator::dateTime(),
            updatedAt: $updatedAt ?? FakeValueGenerator::dateTime()
        );
    }
}
