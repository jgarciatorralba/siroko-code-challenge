<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\Domain;

use App\Carts\Domain\Cart;
use App\Carts\Domain\CartItem;
use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use DateTimeImmutable;

final class CartItemMother
{
    public static function create(
        ?Uuid $id = null,
        ?Cart $cart = null,
        ?Product $product = null,
        ?int $quantity = null,
        ?float $subtotal = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null
    ): CartItem {
        return new CartItem(
            id: $id ?? FakeValueGenerator::uuid(),
            cart: $cart ?? CartMother::create(),
            product: $product ?? ProductMother::create(),
            quantity: $quantity ?? FakeValueGenerator::integer(1),
            subtotal: $subtotal ?? FakeValueGenerator::float(0, 100),
            createdAt: $createdAt ?? FakeValueGenerator::dateTime(),
            updatedAt: $updatedAt ?? FakeValueGenerator::dateTime()
        );
    }
}
