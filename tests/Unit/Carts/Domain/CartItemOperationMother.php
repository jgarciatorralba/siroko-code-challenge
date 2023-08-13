<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\Domain;

use App\Carts\Domain\ValueObject\CartItemOperation;
use App\Products\Domain\Product;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use DateTimeImmutable;

final class CartItemOperationMother
{
    public static function create(
        ?string $type = null,
        ?Product $product = null,
        ?int $quantity = null,
        ?DateTimeImmutable $dateTime = null
    ): CartItemOperation {
        return new CartItemOperation(
            type: $type ?? FakeValueGenerator::string(),
            product: $product ?? ProductMother::create(),
            quantity: $quantity ?? FakeValueGenerator::integer(1),
            dateTime: $dateTime ?? FakeValueGenerator::dateTime()
        );
    }
}
