<?php

declare(strict_types=1);

namespace App\UI\Fixture\Factory;

use App\Shared\Domain\ValueObject\Uuid;
use App\Carts\Domain\CartItem;
use App\Shared\Utils;
use DateTimeImmutable;

final class CartItemFactory extends ModelFactory
{
    protected function getModelClass(): string
    {
        return CartItem::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaultAttributes(): array
    {
        $createdAt = DateTimeImmutable::createFromMutable(
            $this->faker()->dateTimeBetween('-1 year')
        );

        return [
            'id' => Uuid::random(),
            'cart' => CartFactory::new()->createOne(),
            'product' => ProductFactory::new()->createOne(),
            'quantity' => $this->faker()->randomNumber(1, true),
            'createdAt' => $createdAt,
            'updatedAt' => DateTimeImmutable::createFromMutable(
                $this->faker()->dateTimeBetween(Utils::dateToString($createdAt))
            )
        ];
    }
}
