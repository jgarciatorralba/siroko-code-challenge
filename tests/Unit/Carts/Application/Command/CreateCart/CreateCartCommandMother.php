<?php

declare(strict_types=1);

namespace App\Tests\Unit\Carts\Application\Command\CreateCart;

use App\Carts\Application\Command\CreateCart\CreateCartCommand;
use App\Carts\Domain\Cart;
use App\Carts\Domain\CartItem;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use DateTimeImmutable;

final class CreateCartCommandMother
{
    /**
     * @param array<array<string, string|int>> $items
     */
    public static function create(
        ?string $id = null,
        ?array $items = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null
    ): CreateCartCommand {
        return new CreateCartCommand(
            id: $id ?? FakeValueGenerator::uuid()->value(),
            items: $items ?? [],
            createdAt: $createdAt ?? FakeValueGenerator::dateTime(),
            updatedAt: $updatedAt ?? FakeValueGenerator::dateTime()
        );
    }

    public static function createFromCart(Cart $cart): CreateCartCommand
    {
        return self::create(
            id: $cart->id()->value(),
            items: self::mapItemsToArray($cart->items()->toArray()),
            createdAt: $cart->createdAt(),
            updatedAt: $cart->updatedAt()
        );
    }

    /**
     * @param CartItem[] $items
     * @return array<array<string, string|int>>
     */
    private static function mapItemsToArray(array $items): array
    {
        return array_map(
            fn (CartItem $item) => [
                'id' => $item->id()->value(),
                'productId' => $item->product()->id()->value(),
                'quantity' => $item->quantity()
            ],
            $items
        );
    }
}
